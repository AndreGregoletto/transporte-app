<?php

namespace App\Http\Controllers\Transporte\OlhoVivo\Import;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Imports\OlhoVivo\Frequency;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB; // Importação do facade DB

class FrequencyController extends Controller
{
    
    public function importFrequencies(Request $request)
    {
        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '300');

        // Verificar se o arquivo foi enviado
        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'Nenhum arquivo enviado'], 400);
        }

        $file = $request->file('file');

        // Validar extensão
        if (!in_array(strtolower($file->getClientOriginalExtension()), ['txt', 'csv'])) {
            return response()->json(['error' => 'O arquivo deve ser .txt ou .csv'], 400);
        }

        $expectedHeader = ['trip_id', 'start_time', 'end_time', 'headway_secs'];
        $batchSize = 500;
        $batch = [];
        $errors = [];

        try {
            $stream = fopen($file->getRealPath(), 'r');
            if ($stream === false) {
                return response()->json(['error' => 'Não foi possível abrir o arquivo'], 500);
            }

            $header = fgetcsv($stream, 0, ';');
            $header = array_map('trim', (array)$header);

            // Validar cabeçalho
            if ($header !== $expectedHeader) {
                Log::error("Cabeçalho inválido no arquivo {$file->getClientOriginalName()}:", ['lido' => $header, 'esperado' => $expectedHeader]);
                return response()->json(['error' => 'Formato de cabeçalho inválido'], 400);
            }

            $lineCount = 0;
            while (($line = fgetcsv($stream, 0, ';')) !== false) {
                $lineCount++;
                if ($lineCount % 1000 === 0) {
                    Log::info("Processando linha $lineCount do arquivo {$file->getClientOriginalName()}");
                }

                if (count($line) !== 4) {
                    Log::warning("Linha inválida no arquivo {$file->getClientOriginalName()}:", ['linha' => $line]);
                    continue;
                }

                $line = array_map('trim', $line);

                // Validar formato de tempo e headway_secs
                if (!preg_match('/^\d{2}:\d{2}:\d{2}$/', $line[1]) ||
                    !preg_match('/^\d{2}:\d{2}:\d{2}$/', $line[2]) ||
                    !is_numeric($line[3]) || (int)$line[3] <= 0) {
                    Log::warning("Dados inválidos no arquivo {$file->getClientOriginalName()}:", ['linha' => $line]);
                    continue;
                }

                $batch[] = [
                    'trip_id' => $line[0],
                    'start_time' => $line[1],
                    'end_time' => $line[2],
                    'headway_secs' => (int)$line[3],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                if (count($batch) >= $batchSize) {
                    DB::transaction(function () use ($batch) {
                        Frequency::insert($batch);
                    });
                    $batch = [];
                }
            }

            fclose($stream);
        } catch (\Exception $e) {
            Log::error("Erro ao processar arquivo {$file->getClientOriginalName()}: " . $e->getMessage(), ['exception' => $e]);
            return response()->json(['error' => 'Erro ao processar arquivo: ' . $e->getMessage()], 500);
        }

        // Inserir o restante do batch
        if (!empty($batch)) {
            DB::transaction(function () use ($batch) {
                Frequency::insert($batch);
            });
        }

        if (!empty($errors)) {
            return response()->json(['error' => 'Erros ocorreram durante a importação', 'details' => $errors], 500);
        }

        return response()->json(['message' => 'Frequências importadas com sucesso']);
    }

    public function index()
    {
        try {
            $frequencies = Frequency::all();
            return response()->json($frequencies);
        } catch (\Throwable $th) {
            Log::error('Erro ao listar frequências: ' . $th->getMessage(), ['exception' => $th]);
            return response()->json(['error' => 'Erro ao listar frequências'], 500);
        }
    }

    public function showLine($tripId)
    {
        try {
            $frequency = Frequency::where('trip_id', 'like', $tripId . '%')
                ->select('trip_id', 'start_time', 'end_time', 'headway_secs')
                ->get();

            if ($frequency->isEmpty()) {
                return response()->json([], 404);
            }
            return response()->json($frequency);
        } catch (\Throwable $th) {
            Log::error('Erro ao buscar frequência: ' . $th->getMessage(), ['exception' => $th]);
            return [];
        }
    }
}