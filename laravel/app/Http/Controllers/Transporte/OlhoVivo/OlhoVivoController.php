<?php
namespace App\Http\Controllers\Transporte\OlhoVivo;

use App\Http\Controllers\Controller;
use App\Services\OlhoVivoServices;
use Illuminate\Http\Request;
use App\Http\Controllers\Transporte\OlhoVivo\MyBusController;

class OlhoVivoController extends Controller
{
    private $olhoVivoService;
    private $myBusController;

    public function __construct(
        OlhoVivoServices $olhoVivoService,
        MyBusController $myBusController
    ) {
        $this->olhoVivoService = $olhoVivoService;
        $this->myBusController = $myBusController;
    }

    public function index($aResponse = null, $error = null)
    {
        $myLines = $this->myBusController->index();
        $search  = session('search', '');

        return view('pages.transport.olhoVivo.search', [
            'title'     => 'Olho Vivo',
            'subtitle'  => 'Consulta de linhas de ônibus',
            'aResponse' => $aResponse ?? [],
            'myLines'   => $myLines->getData() ?? [],
            'error'     => $error,
            'search'    => $search, 
            'success'   => '',
        ]);
    }

    public function search(Request $request, $error = null, $success = null)
    {
        
        $iLine     = $request->input('search');
        $aLines    = $this->olhoVivoService->getLines($iLine);
        $aResponse = [];
        $sl        = "";

        session(['search' => $iLine]);

        $msgErro = empty($aLines) ? 'Nenhuma linha encontrada para a consulta.' : null;

        if ($msgErro == null) {
            foreach ($aLines as $aLine) {
                $sl = $aLine['sl'];
                $valueTp = $sl == 1 ? 'tp' : 'ts';

                $aResponse[$aLine['cl']] = [
                    'name' => "{$aLine['lt']}-{$aLine['tl']} {$aLine[$valueTp]}",
                    'lc'  => $aLine['lc'] == false ? 'N' : $aLine['lc'],
                    'lt'  => $aLine['lt'],
                    'sl'  => $aLine['sl'],
                    'tl'  => $aLine['tl'],
                    'tp'  => $aLine['tp'],
                    'ts'  => $aLine['ts'],
                ];
            }
        }

        $error = $msgErro ?? $error;

        $myLines = $this->myBusController->index();
        
        return view('pages.transport.olhoVivo.search', [
            'title'     => 'Olho Vivo',
            'subtitle'  => 'Consulta de linhas de ônibus',
            'myLines'   => $myLines->getData() ?? [],
            'aResponse' => $aResponse,
            'error'     => $error,
            'search'    => $iLine,
            'success'   => $success,
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
            $error = 'Linha já adicionada.';
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

    public function removeLine(Request $request)
    {
        return response()->json(['message' => 'Remove line functionality not implemented yet.']);
    }

    public function getLines(Request $request)
    {
        return response()->json(['message' => 'Get lines functionality not implemented yet.']);
    }
}