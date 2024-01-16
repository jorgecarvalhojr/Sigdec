<?php

namespace App\Exports;

use App\Models\atividades;
use App\Models\orgao;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class est_ciclo implements FromView
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

        $ano = substr($data_inicial, 6, 4);
        if($orgao == 'todos')
        {
            $atividades=DB::select(DB::raw("Select ciclo, count(ciclo) as quantidade from atividades_dc where DATE_FORMAT(data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d')."' group by ciclo order by ciclo"));
            $nome_orgao = "TODOS";
            $anual = atividades::whereyear('data_inicio', $ano)
                                ->selectRaw("SUM(month(data_inicio) = 1) AS janeiro, SUM(month(data_inicio) = 2) AS fevereiro, SUM(month(data_inicio) = 3) AS marco, SUM(month(data_inicio) = 4) AS abril, SUM(month(data_inicio) = 5) AS maio, SUM(month(data_inicio) = 6) AS junho, SUM(month(data_inicio) = 7) AS julho, SUM(month(data_inicio) = 8) AS agosto, SUM(month(data_inicio) = 9) AS setembro, SUM(month(data_inicio) = 10) AS outubro, SUM(month(data_inicio) = 11) AS novembro, SUM(month(data_inicio) = 12) AS dezembro, SUM(year(data_inicio)='".$ano."') as total, ciclo")
                                ->groupBy('ciclo')
                                ->orderBy('ciclo')
                                ->cursor();

        }else
        {
            $atividades=DB::select(DB::raw("Select ciclo, count(ciclo) as quantidade from atividades_dc where DATE_FORMAT(data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d')."' and orgao='".$orgao."' group by ciclo order by ciclo"));
            $nome_orgao = orgao::findorfail($orgao) -> sigla;
            $anual = atividades::whereyear('data_inicio', $ano)
                                ->where('orgao', $orgao)
                                ->selectRaw("SUM(month(data_inicio) = 1) AS janeiro, SUM(month(data_inicio) = 2) AS fevereiro, SUM(month(data_inicio) = 3) AS marco, SUM(month(data_inicio) = 4) AS abril, SUM(month(data_inicio) = 5) AS maio, SUM(month(data_inicio) = 6) AS junho, SUM(month(data_inicio) = 7) AS julho, SUM(month(data_inicio) = 8) AS agosto, SUM(month(data_inicio) = 9) AS setembro, SUM(month(data_inicio) = 10) AS outubro, SUM(month(data_inicio) = 11) AS novembro, SUM(month(data_inicio) = 12) AS dezembro, SUM(year(data_inicio)='".$ano."') as total, ciclo")
                                ->groupBy('ciclo')
                                ->orderBy('ciclo')
                                ->cursor();
        }

        return view('auth.exports.est_ciclo', ['data_inicial' => $data_inicial, 'data_final' => $data_final, 'atividades' => $atividades, 'nome_orgao' => $nome_orgao, 'anual' => $anual]);

    }
}
