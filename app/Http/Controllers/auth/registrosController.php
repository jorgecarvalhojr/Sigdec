<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\atividades;
use App\Models\orgao;
use Illuminate\Http\Request;

class registrosController extends Controller
{
    public function index()
    {
        $orgaos = orgao::orderBy('descricao')-> get();
        
        return \view('auth.registros')->with('orgaos', $orgaos);
    }

    /*******************************************************************************/
    public function show($id)
    {
        $atividades = atividades::where('orgao', $id)-> orderBy('data_inicio', 'desc')-> get();

        $orgao = orgao::findOrFail($id);
        
        return \view('auth.atividades_orgao')->with('orgao', $orgao)
                                      ->with('atividades', $atividades);
    }

    /*******************************************************************************/
}
