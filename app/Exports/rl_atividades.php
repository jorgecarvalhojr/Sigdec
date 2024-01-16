<?php

namespace App\Exports;

use App\Models\atividades;
use App\Models\orgao;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class rl_atividades implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $data_inicial;

    private $data_final;

    private $orgao;

    public function __construct($data_inicial, $data_final, $orgao)
    {
        $this->data_inicial = $data_inicial;
        $this->data_final = $data_final;
        $this->orgao = $orgao;
    }


    public function view(): View
    {
        $data_inicial = $this -> data_inicial;
        $data_final = $this -> data_final;
        $orgao = $this -> orgao;

        if($orgao == 'todos')
        {
            $atividades = atividades::where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), ">=", Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d'))
                                    ->where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), "<=", Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d'))
                                    ->orderBy('data_inicio')->cursor();
            $nome_orgao = "TODOS";
        }else{
            $atividades = atividades::where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), ">=", Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d'))
                                    ->where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), "<=", Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d'))
                                    ->where('orgao',$orgao)
                                    ->orderBy('data_inicio')->cursor();
            $nome_orgao = orgao::findorfail($orgao) -> sigla;
        }

        return view('auth.exports.rl_atividades', ['data_inicial' => $data_inicial, 'data_final' => $data_final, 'atividades' => $atividades, 'nome_orgao' => $nome_orgao]);

    }
}
