<?php

namespace App\Exports;

use App\Models\atividades;
use App\Models\orgao;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;

class est_quilometros implements FromView
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
            $km = DB::select(DB::raw("Select Distinct vt.prefixo as viatura, vt.id_viatura as id_vtr, sum(op.kilometragem) as km from op_viatura op left join atividades_dc at on op.id_atv=at.id_atv left join viaturas vt on op.id_viatura=vt.id_viatura where DATE_FORMAT(at.data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d')."' and op.ativo='Sim' group by op.id_viatura order by vt.prefixo"));
            $orgao = "TODOS";
            $anual = atividades::whereyear('data_inicio', $ano)
                                ->leftjoin('op_viatura', 'atividades_dc.id_atv', '=', 'op_viatura.id_atv')
                                ->leftjoin('viaturas', 'op_viatura.id_viatura', '=', 'viaturas.id_viatura')
                                ->selectRaw("SUM(CASE WHEN month(data_inicio) = 1 THEN op_viatura.kilometragem ELSE 0 END) AS janeiro, SUM(CASE WHEN month(data_inicio) = 2 THEN op_viatura.kilometragem ELSE 0 END) AS fevereiro, SUM(CASE WHEN month(data_inicio) = 3 THEN op_viatura.kilometragem ELSE 0 END) AS marco, SUM(CASE WHEN month(data_inicio) = 4 THEN op_viatura.kilometragem ELSE 0 END) AS abril, SUM(CASE WHEN month(data_inicio) = 5 THEN op_viatura.kilometragem ELSE 0 END) AS maio, SUM(CASE WHEN month(data_inicio) = 6 THEN op_viatura.kilometragem ELSE 0 END) AS junho, SUM(CASE WHEN month(data_inicio) = 7 THEN op_viatura.kilometragem ELSE 0 END) AS julho, SUM(CASE WHEN month(data_inicio) = 8 THEN op_viatura.kilometragem ELSE 0 END) AS agosto, SUM(CASE WHEN month(data_inicio) = 9 THEN op_viatura.kilometragem ELSE 0 END) AS setembro, SUM(CASE WHEN month(data_inicio) = 10 THEN op_viatura.kilometragem ELSE 0 END) AS outubro, SUM(CASE WHEN month(data_inicio) = 11 THEN op_viatura.kilometragem ELSE 0 END) AS novembro, SUM(CASE WHEN month(data_inicio) = 12 THEN op_viatura.kilometragem ELSE 0 END) AS dezembro, SUM(CASE WHEN year(data_inicio)='".$ano."' THEN op_viatura.kilometragem ELSE 0 END) as total, viaturas.prefixo as viatura")
                                ->where('viaturas.prefixo', '!=', '')
                                ->where('op_viatura.ativo', 'Sim')
                                ->groupBy('viaturas.prefixo')
                                ->orderBy('viaturas.prefixo')
                                ->cursor();
        }else{
            $km = DB::select(DB::raw("Select Distinct vt.prefixo as viatura, vt.id_viatura as id_vtr, sum(op.kilometragem) as km from op_viatura op left join atividades_dc at on op.id_atv=at.id_atv left join viaturas vt on op.id_viatura=vt.id_viatura where DATE_FORMAT(at.data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d')."' and at.orgao='".$orgao."' and op.ativo='Sim' group by op.id_viatura order by vt.prefixo"));
            $nome_orgao = orgao::findorfail($orgao) -> sigla;
            $anual = atividades::whereyear('data_inicio', $ano)
                                ->where('orgao', $orgao)
                                ->leftjoin('op_viatura', 'atividades_dc.id_atv', '=', 'op_viatura.id_atv')
                                ->leftjoin('viaturas', 'op_viatura.id_viatura', '=', 'viaturas.id_viatura')
                                ->selectRaw("SUM(CASE WHEN month(data_inicio) = 1 THEN op_viatura.kilometragem ELSE 0 END) AS janeiro, SUM(CASE WHEN month(data_inicio) = 2 THEN op_viatura.kilometragem ELSE 0 END) AS fevereiro, SUM(CASE WHEN month(data_inicio) = 3 THEN op_viatura.kilometragem ELSE 0 END) AS marco, SUM(CASE WHEN month(data_inicio) = 4 THEN op_viatura.kilometragem ELSE 0 END) AS abril, SUM(CASE WHEN month(data_inicio) = 5 THEN op_viatura.kilometragem ELSE 0 END) AS maio, SUM(CASE WHEN month(data_inicio) = 6 THEN op_viatura.kilometragem ELSE 0 END) AS junho, SUM(CASE WHEN month(data_inicio) = 7 THEN op_viatura.kilometragem ELSE 0 END) AS julho, SUM(CASE WHEN month(data_inicio) = 8 THEN op_viatura.kilometragem ELSE 0 END) AS agosto, SUM(CASE WHEN month(data_inicio) = 9 THEN op_viatura.kilometragem ELSE 0 END) AS setembro, SUM(CASE WHEN month(data_inicio) = 10 THEN op_viatura.kilometragem ELSE 0 END) AS outubro, SUM(CASE WHEN month(data_inicio) = 11 THEN op_viatura.kilometragem ELSE 0 END) AS novembro, SUM(CASE WHEN month(data_inicio) = 12 THEN op_viatura.kilometragem ELSE 0 END) AS dezembro, SUM(CASE WHEN year(data_inicio)='".$ano."' THEN op_viatura.kilometragem ELSE 0 END) as total, viaturas.prefixo as viatura")
                                ->where('viaturas.prefixo', '!=', '')
                                ->where('op_viatura.ativo', 'Sim')
                                ->groupBy('viaturas.prefixo')
                                ->orderBy('viaturas.prefixo')
                                ->cursor();

        }

        return view('auth.exports.est_quilometros', ['data_inicial' => $data_inicial, 'data_final' => $data_final, 'km' => $km, 'nome_orgao' => $nome_orgao, 'anual' => $anual]);

    }
}
