<?php

namespace App\Exports;

use App\Models\atividades;
use App\Models\orgao;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class est_atividades implements FromView
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
            $atividades=DB::select(DB::raw("Select lt.atividade, atv.tipo_atividade, count(atv.tipo_atividade) as quantidade from atividades_dc atv left join lista_atividades lt on atv.tipo_atividade=lt.id_atividade where DATE_FORMAT(atv.data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d')."' group by atv.tipo_atividade order by lt.atividade"));
            $nome_orgao = "TODOS";
            $anual = atividades::whereyear('data_inicio', $ano)
                                ->leftjoin('lista_atividades', 'atividades_dc.tipo_atividade', '=', 'lista_atividades.id_atividade')
                                ->selectRaw("SUM(month(data_inicio) = 1) AS janeiro, SUM(month(data_inicio) = 2) AS fevereiro, SUM(month(data_inicio) = 3) AS marco, SUM(month(data_inicio) = 4) AS abril, SUM(month(data_inicio) = 5) AS maio, SUM(month(data_inicio) = 6) AS junho, SUM(month(data_inicio) = 7) AS julho, SUM(month(data_inicio) = 8) AS agosto, SUM(month(data_inicio) = 9) AS setembro, SUM(month(data_inicio) = 10) AS outubro, SUM(month(data_inicio) = 11) AS novembro, SUM(month(data_inicio) = 12) AS dezembro, SUM(year(data_inicio)='".$ano."') as total, lista_atividades.atividade")
                                ->groupBy('atividades_dc.tipo_atividade')
                                ->orderBy('lista_atividades.atividade')
                                ->cursor();

        }else
        {
            $atividades=DB::select(DB::raw("Select lt.atividade, atv.tipo_atividade, count(atv.tipo_atividade) as quantidade from atividades_dc atv left join lista_atividades lt on atv.tipo_atividade=lt.id_atividade where DATE_FORMAT(atv.data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d')."' and atv.orgao='".$orgao."' group by atv.tipo_atividade order by lt.atividade"));
            $nome_orgao = orgao::findorfail($orgao) -> sigla;
            $anual = atividades::whereyear('data_inicio', $ano)
                                ->where('orgao', $orgao)
                                ->leftjoin('lista_atividades', 'atividades_dc.tipo_atividade', '=', 'lista_atividades.id_atividade')
                                ->selectRaw("SUM(month(data_inicio) = 1) AS janeiro, SUM(month(data_inicio) = 2) AS fevereiro, SUM(month(data_inicio) = 3) AS marco, SUM(month(data_inicio) = 4) AS abril, SUM(month(data_inicio) = 5) AS maio, SUM(month(data_inicio) = 6) AS junho, SUM(month(data_inicio) = 7) AS julho, SUM(month(data_inicio) = 8) AS agosto, SUM(month(data_inicio) = 9) AS setembro, SUM(month(data_inicio) = 10) AS outubro, SUM(month(data_inicio) = 11) AS novembro, SUM(month(data_inicio) = 12) AS dezembro, SUM(year(data_inicio)='".$ano."') as total, lista_atividades.atividade")
                                ->groupBy('atividades_dc.tipo_atividade')
                                ->orderBy('lista_atividades.atividade')
                                ->cursor();
        }

        return view('auth.exports.est_atividades', ['data_inicial' => $data_inicial, 'data_final' => $data_final, 'atividades' => $atividades, 'nome_orgao' => $nome_orgao, 'anual' => $anual]);

    }
}
