<?php

namespace App\Http\Controllers\Transporte\OlhoVivo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transporte\OlhoVivo\MyBus;
use App\Services\OlhoVivoServices;

class MyBusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $aResponse = MyBus::with('user')
                ->whereUserId(auth()->id())
                ->whereStatus(true) 
                ->get();
    
            return response()->json($aResponse);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao buscar as linhas: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store($aResponse)
    {
        try {
            $myBus = new MyBus();
            $myBus->create($aResponse);
    
            return response()->json([
                'message' => 'Linha adicionada com sucesso.',
                'data'    => $myBus
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Erro ao adicionar a linha: ' . $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // dd(MyBus::get());
        try {
            $myBus = MyBus::with('user')
                ->whereCl($id)
                ->whereUserId(auth()->id())
                ->firstOrFail();
            return response()->json($myBus);
            
        } catch (\Throwable $th) {
            return response()->json([
                'error' => 'Linha não encontrada ou você não tem permissão para visualizar esta linha.'
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
