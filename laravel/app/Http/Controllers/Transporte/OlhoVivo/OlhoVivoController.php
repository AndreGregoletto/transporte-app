<?php
namespace App\Http\Controllers\Transporte\OlhoVivo;

use App\Http\Controllers\Controller;
use App\Services\OlhoVivoServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Transporte\OlhoVivo\MyBusController;
use App\Models\Imports\OlhoVivo\Frequency;
use App\Http\Controllers\Transporte\OlhoVivo\Import\FrequencyController;

class OlhoVivoController extends Controller
{
    private $olhoVivoService;
    private $myBusController;
    private $frequencyController;

    public function __construct(
        OlhoVivoServices    $olhoVivoService,
        MyBusController     $myBusController,
        FrequencyController $frequencyController,
    ) {
        $this->olhoVivoService     = $olhoVivoService;
        $this->myBusController     = $myBusController;
        $this->frequencyController = $frequencyController;
    }

    public function index($aResponse = null, $error = null)
    {
        $search  = session('search', '');
        $myLines = $this->myBusController->index(auth()->id())->getData() ?? [];

        return view('pages.transport.olhoVivo.search', [
            'title'           => 'Olho Vivo',
            'subtitle'        => 'Consulta de linhas de ônibus',
            'aResponse'       => $aResponse ?? [],
            'myLines'         => $myLines,
            'error'           => $error,
            'search'          => $search, 
            'success'         => '',
            'userFrequencies' => $this->getFrequencies($myLines),
            'frequencies'     => [],
        ]);
    }

    public function removeMyLine($aSearch)
    {
        $myLines   = $this->myBusController->index(auth()->id())->getData();
        $aResponse = [];

        if(!empty($myLines)) {
            foreach ($myLines as $myLine) {
                $aResponse[$myLine->cl] = $myLine;
            }
        }

        return $aResponse;
    }

    public function search(Request $request, $error = null, $success = null)
    {
        $myLines   = $this->myBusController->index(auth()->id())->getData() ?? [];
        $iLine     = $request->input('search');
        $aLines    = $this->olhoVivoService->getLines($iLine);
        $aResponse = [];
        $sl        = "";

        session(['search' => $iLine]);

        $msgErro = empty($aLines) ? 'Nenhuma linha encontrada para a consulta.' : null;

        if ($msgErro == null) {
            $aMyLines = $this->removeMyLine($iLine);

            foreach ($aLines as $aLine) {
                if ($aMyLines[$aLine['cl']] ?? false) continue;

                $sl = $aLine['sl'];
                $valueTp = $sl == 1 ? 'tp' : 'ts';

                $aResponse[$aLine['cl']] = [
                    'name' => "{$aLine['lt']}-{$aLine['tl']} {$aLine[$valueTp]}",
                    'lc' => $aLine['lc'] == false ? 'N' : $aLine['lc'],
                    'lt' => $aLine['lt'],
                    'sl' => $aLine['sl'],
                    'tl' => $aLine['tl'],
                    'tp' => $aLine['tp'],
                    'ts' => $aLine['ts'],
                ];
            }
        }

        $error = $msgErro ?? $error;
        $error = empty($aResponse) ? 'Nenhuma linha encontrada.' : $error;
        // Converter $aResponse em array de objetos stdClass
        $oResponse = array_map(function ($item) {
            return (object) $item;
        }, array_values($aResponse));

        return view('pages.transport.olhoVivo.search', [
            'title'           => 'Olho Vivo',
            'subtitle'        => 'Consulta de linhas de ônibus',
            'myLines'         => $myLines,
            'aResponse'       => $aResponse,
            'error'           => $error,
            'search'          => $iLine,
            'success'         => $success,
            'userFrequencies' => $this->getFrequencies($myLines),
            'frequencies'     => $this->getFrequencies($oResponse),
        ]);
    }

    public function addLine(Request $request, $cl, $lc, $lt, $sl, $tl, $tp, $ts, $name_bus)
    {
        $aResponse = [];
        $success   = null;
        $error     = null;
        $myBus     = $this->myBusController->show($cl);
        $search    = session('search', '');
        $request->merge(['search' => $search]);

        if ($myBus->getStatusCode() == 200) {
            $response = $this->myBusController->update($cl);
            if($response->getStatusCode() == 200) {
                $success = 'Linha adicionada com sucesso.';
            }
        }

        if ($myBus->getStatusCode() == 404) {
            $aSave = [
                'cl' => $cl,
                'lc' => $lc,
                'lt' => $lt,
                'sl' => $sl,
                'tl' => $tl,
                'tp' => $tp,
                'ts' => $ts,
                'name_bus' => $name_bus,
                'user_id' => auth()->id(),
            ];

            $addLine = $this->myBusController->store($aSave);
            $success = $addLine->getStatusCode() == 201 ? 'Linha adicionada com sucesso.' : null;
        }

        return $this->search($request, $error, $success);
    }

    public function removeLine(Request $request, $id, $cl)
    {
        $error   = null;
        $success = null;
        $myBus   = $this->myBusController->show($cl);
        $search  = session('search', '');
        $request->merge(['search' => $search]);

        try {
            if ($myBus->getStatusCode() != 200) {
                throw new \Exception('Linha não encontrada.');
            } 

            $response = $this->myBusController->destroy($id, $cl);

            if($response->getStatusCode() == 200) {
                $success = 'Linha removida com sucesso.';
            } else {
                $error = 'Erro ao remover a linha.';
            }
        } catch (\Throwable $th) {
            $error = 'Linha não encontrada.';
        }

        return $this->search($request, $error, $success);
    }

    public function getFrequencies($myLines)
    {
        $aFrequencies = [];

        if (!is_array($myLines)) {
            $myLines = [$myLines];
        }

        foreach ($myLines as $line) {
            if (!isset($line->sl, $line->lt, $line->tl)) {
                throw new \InvalidArgumentException('Objeto de linha inválido: propriedades sl, lt ou tl ausentes');
            }

            $aTripId = $line->lt . '-' . $line->tl;
            $aFrequencies[$aTripId] = [
                'weekend' => $this->frequencyController->showLine($aTripId . '-1')->getData(),
                'week'    => $this->frequencyController->showLine($aTripId . '-0')->getData(),
            ];
        }

        return $aFrequencies;
    }
}