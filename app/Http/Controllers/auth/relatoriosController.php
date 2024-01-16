<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Exports\est_atividades;
use App\Exports\est_ciclo;
use App\Exports\est_quilometros;
use App\Exports\rl_atividades;
use App\Models\atividades;
use App\Models\op_viatura;
use App\Models\orgao;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;


class relatoriosController extends Controller
{
    public function index() 
    {

        $orgaos = DB::select(DB::raw("Select distinct ad.orgao, og.sigla, og.descricao From atividades_dc ad left join orgao og on ad.orgao=og.id_orgao order by og.sigla"));

        return \view('auth.relatorios') -> with('orgaos', $orgaos);
    }
//********************************************************************************************/
    public function show(Request $request) 
    {
        $request -> validate([
            'opcao' => 'required',
            'data_inicial' => 'required',
            'data_final' => 'required',
            'orgao' => 'required',
        ]);

        switch($request -> opcao)
        {
            case '1':
                if($request -> orgao == 'todos')
                {
                    $atividades = atividades::where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), ">=", Carbon::createFromFormat('d/m/Y', $request->data_inicial)->format('Y-m-d'))
                                            ->where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), "<=", Carbon::createFromFormat('d/m/Y', $request->data_final)->format('Y-m-d'))
                                            ->orderBy('data_inicio')->cursor();
                    $orgao = "TODOS";
                }else{
                    $atividades = atividades::where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), ">=", Carbon::createFromFormat('d/m/Y', $request->data_inicial)->format('Y-m-d'))
                                            ->where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), "<=", Carbon::createFromFormat('d/m/Y', $request->data_final)->format('Y-m-d'))
                                            ->where('orgao',$request -> orgao)
                                            ->orderBy('data_inicio')->cursor();
                    $orgao = orgao::findorfail($request -> orgao) -> sigla;
                }

                if(count($atividades) === 0){
                    return \back()->with('warning', 'Nenhum resultado encontrado!');
                }

                return \view('auth.rl_atividades')-> with('atividades', $atividades)
                                                    -> with('data_inicial', $request -> data_inicial)
                                                    -> with('data_final', $request -> data_final)
                                                    -> with('orgao', $request -> orgao)
                                                    -> with('nome_orgao', $orgao);
                break;

            case '2':
                break;
    
            case '3':
                break;

        }
    }

    public function export_xls_atividades($data_inicial, $data_final, $orgao) 
    {
        return Excel::download(new rl_atividades($data_inicial, $data_final, $orgao), 'Atividades.xlsx');
    }

    public function export_pdf_atividades($data_inicial, $data_final, $orgao) 
    {
        \set_time_limit(0);
        if($orgao == 'todos')
        {
            $atividades = atividades::where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), ">=", Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d'))
                                    ->where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), "<=", Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d'))
                                    ->orderBy('data_inicio')->cursor();
            $orgao = "TODOS";
        }else{
            $atividades = atividades::where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), ">=", Carbon::createFromFormat('d-m-Y', $data_inicial)->format('Y-m-d'))
                                    ->where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), "<=", Carbon::createFromFormat('d-m-Y', $data_final)->format('Y-m-d'))
                                    ->where('orgao',$orgao)
                                    ->orderBy('data_inicio')->cursor();
            $orgao = orgao::findorfail($orgao) -> sigla;
        }

        $viaturas = op_viatura::where('ativo','sim')->cursor();
        $pdf = PDF::loadView('auth.exports.rl_atividades_pdf',['data_inicial' => $data_inicial, 'data_final' => $data_final, 'orgao' => $orgao, 'atividades' => $atividades, 'viaturas' => $viaturas]) ; 
        $options = $pdf->getOptions();
        $options->set('isPhpEnabled', true);
        $pdf->setPaper('A4', 'Portrait');
        $pdf->getDOMPdf()->setOptions($options);            
        return $pdf->stream('Atividades.pdf');
    }

}