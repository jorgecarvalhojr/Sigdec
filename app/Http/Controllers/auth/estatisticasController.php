<?php

namespace App\Http\Controllers\auth;

use App\Exports\est_atividades;
use App\Exports\est_ciclo;
use App\Exports\est_quilometros;
use App\Http\Controllers\Controller;
use App\Models\atividades;
use App\Models\orgao;
use App\Models\redec;
use BarPlot;
use Facade\FlareClient\Http\Response;
use Graph;
use Illuminate\Support\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use mitoteam\jpgraph\MtJpGraph;
use PieGraph;
use PiePlot3D;

class estatisticasController extends Controller
{
        public function index() 
    {

        $orgaos = DB::select(DB::raw("Select distinct ad.orgao, og.sigla, og.descricao From atividades_dc ad left join orgao og on ad.orgao=og.id_orgao order by og.sigla"));

        return \view('auth.estatisticas') -> with('orgaos', $orgaos);
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

        $ano = substr($request -> data_inicial, 6, 4);
        switch($request -> opcao)
        {
            case '1':
                MtJpGraph::load(['bar'], true);
                if($request -> orgao == 'todos')
                {
                    $km = DB::select(DB::raw("Select Distinct vt.prefixo as viatura, vt.id_viatura as id_vtr, sum(op.kilometragem) as km from op_viatura op left join atividades_dc at on op.id_atv=at.id_atv left join viaturas vt on op.id_viatura=vt.id_viatura where DATE_FORMAT(at.data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d/m/Y', $request->data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d/m/Y', $request->data_final)->format('Y-m-d')."' and op.ativo='Sim' group by op.id_viatura order by vt.prefixo"));
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
                    $km = DB::select(DB::raw("Select Distinct vt.prefixo as viatura, vt.id_viatura as id_vtr, sum(op.kilometragem) as km from op_viatura op left join atividades_dc at on op.id_atv=at.id_atv left join viaturas vt on op.id_viatura=vt.id_viatura where DATE_FORMAT(at.data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d/m/Y', $request->data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d/m/Y', $request->data_final)->format('Y-m-d')."' and at.orgao='".$request -> orgao."' and op.ativo='Sim' group by op.id_viatura order by vt.prefixo"));
                    $orgao = orgao::findorfail($request -> orgao) -> sigla;
                    $anual = atividades::whereyear('data_inicio', $ano)
                                        ->where('orgao', $request -> orgao)
                                        ->leftjoin('op_viatura', 'atividades_dc.id_atv', '=', 'op_viatura.id_atv')
                                        ->leftjoin('viaturas', 'op_viatura.id_viatura', '=', 'viaturas.id_viatura')
                                        ->selectRaw("SUM(CASE WHEN month(data_inicio) = 1 THEN op_viatura.kilometragem ELSE 0 END) AS janeiro, SUM(CASE WHEN month(data_inicio) = 2 THEN op_viatura.kilometragem ELSE 0 END) AS fevereiro, SUM(CASE WHEN month(data_inicio) = 3 THEN op_viatura.kilometragem ELSE 0 END) AS marco, SUM(CASE WHEN month(data_inicio) = 4 THEN op_viatura.kilometragem ELSE 0 END) AS abril, SUM(CASE WHEN month(data_inicio) = 5 THEN op_viatura.kilometragem ELSE 0 END) AS maio, SUM(CASE WHEN month(data_inicio) = 6 THEN op_viatura.kilometragem ELSE 0 END) AS junho, SUM(CASE WHEN month(data_inicio) = 7 THEN op_viatura.kilometragem ELSE 0 END) AS julho, SUM(CASE WHEN month(data_inicio) = 8 THEN op_viatura.kilometragem ELSE 0 END) AS agosto, SUM(CASE WHEN month(data_inicio) = 9 THEN op_viatura.kilometragem ELSE 0 END) AS setembro, SUM(CASE WHEN month(data_inicio) = 10 THEN op_viatura.kilometragem ELSE 0 END) AS outubro, SUM(CASE WHEN month(data_inicio) = 11 THEN op_viatura.kilometragem ELSE 0 END) AS novembro, SUM(CASE WHEN month(data_inicio) = 12 THEN op_viatura.kilometragem ELSE 0 END) AS dezembro, SUM(CASE WHEN year(data_inicio)='".$ano."' THEN op_viatura.kilometragem ELSE 0 END) as total, viaturas.prefixo as viatura")
                                        ->where('viaturas.prefixo', '!=', '')
                                        ->where('op_viatura.ativo', 'Sim')
                                        ->groupBy('viaturas.prefixo')
                                        ->orderBy('viaturas.prefixo')
                                        ->cursor();

                }

                if(count($km) === 0){
                    return \back()->with('warning', 'Nenhum resultado encontrado!');
                }

                $dadosx = array();
                $dadosy = array();
                foreach($km as $row){
                    $dadosx[] = $row -> viatura;
                    $dadosy[] = $row -> km;
                } 
                
                // Create the graph. 
                // One minute timeout for the cached image
                // INLINE_NO means don't stream it back to the browser.
                $graph1 = new Graph(800,400, "auto");
                $graph1->SetScale("textlin");
                $graph1->img->SetMargin(60,30,20,80);
                $graph1->yaxis->SetTitleMargin(45);
                $graph1->yaxis->scale->SetGrace(30);
                $graph1->SetShadow();
        
                // Turn the tickmarks
                $graph1->xaxis->SetTickSide(SIDE_DOWN);
                $graph1->yaxis->SetTickSide(SIDE_LEFT);
                $graph1->xaxis->SetTickLabels($dadosx);
                $graph1->xaxis->SetLabelAngle(25);
                
                $bplot = new BarPlot($dadosy);
                $bplot->SetFillGradient("#000033","#F5703C",GRAD_LEFT_REFLECTION);
                $bplot->SetColor("black");
                $bplot->SetShadow();
        
                // Create a bar pot
                
                $graph1->Add($bplot);
        
                $graph1->title->Set("QUILÔMETROS RODADOS ENTRE ".$request -> data_inicial." e ".$request -> data_final." - ÓRGÃO: ".$orgao);
                // $graph1->xaxis->title->Set("Viatura");
                // $graph1->yaxis->title->Set("KM");
                $graph1->title->SetFont(FF_VERDANA,FS_BOLD,8);
                $graph1->title->SetColor("black");
        
                $graph1->yaxis->title->SetFont(FF_VERDANA,FS_BOLD,9);
                $graph1->xaxis->title->SetFont(FF_VERDANA,FS_BOLD,9);
                $graph1->xaxis->SetFont(FF_VERDANA,FS_NORMAL,9);
                $graph1->yaxis->SetFont(FF_VERDANA,FS_NORMAL,9);
        
                // Send back the HTML page which will call this script again
                // to retrieve the image.
                $contentType = 'image/png';
                ob_start();
                $graph1->Stroke();
                $image_data = ob_get_clean();
                
                $str = "data:$contentType;base64," . base64_encode($image_data);
                // $response = $graph1->Stroke(_IMG_HANDLER);
                
                return \view('auth.est_km')-> with('km', $km)
                                                    -> with('grafico1', $str)
                                                    -> with('data_inicial', $request -> data_inicial)
                                                    -> with('data_final', $request -> data_final)
                                                    -> with('orgao', $request -> orgao)
                                                    -> with('nome_orgao', $orgao)
                                                    -> with('anual', $anual);
                break;

            case '2':
                MtJpGraph::load(['pie', 'pie3d'], true);
                if($request -> orgao == 'todos')
                {
                    $atividades=DB::select(DB::raw("Select lt.atividade, atv.tipo_atividade, count(atv.tipo_atividade) as quantidade from atividades_dc atv left join lista_atividades lt on atv.tipo_atividade=lt.id_atividade where DATE_FORMAT(atv.data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d/m/Y', $request->data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d/m/Y', $request->data_final)->format('Y-m-d')."' group by atv.tipo_atividade order by lt.atividade"));
		            $orgao = "TODOS";
                    $anual = atividades::whereyear('data_inicio', $ano)
                                        ->leftjoin('lista_atividades', 'atividades_dc.tipo_atividade', '=', 'lista_atividades.id_atividade')
                                        ->selectRaw("SUM(month(data_inicio) = 1) AS janeiro, SUM(month(data_inicio) = 2) AS fevereiro, SUM(month(data_inicio) = 3) AS marco, SUM(month(data_inicio) = 4) AS abril, SUM(month(data_inicio) = 5) AS maio, SUM(month(data_inicio) = 6) AS junho, SUM(month(data_inicio) = 7) AS julho, SUM(month(data_inicio) = 8) AS agosto, SUM(month(data_inicio) = 9) AS setembro, SUM(month(data_inicio) = 10) AS outubro, SUM(month(data_inicio) = 11) AS novembro, SUM(month(data_inicio) = 12) AS dezembro, SUM(year(data_inicio)='".$ano."') as total, lista_atividades.atividade")
                                        ->groupBy('atividades_dc.tipo_atividade')
                                        ->orderBy('lista_atividades.atividade')
                                        ->cursor();

                }else
                {
                    $atividades=DB::select(DB::raw("Select lt.atividade, atv.tipo_atividade, count(atv.tipo_atividade) as quantidade from atividades_dc atv left join lista_atividades lt on atv.tipo_atividade=lt.id_atividade where DATE_FORMAT(atv.data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d/m/Y', $request->data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d/m/Y', $request->data_final)->format('Y-m-d')."' and atv.orgao='".$request -> orgao."' group by atv.tipo_atividade order by lt.atividade"));
                    $orgao = orgao::findorfail($request -> orgao) -> sigla;
                    $anual = atividades::whereyear('data_inicio', $ano)
                                        ->where('orgao', $request -> orgao)
                                        ->leftjoin('lista_atividades', 'atividades_dc.tipo_atividade', '=', 'lista_atividades.id_atividade')
                                        ->selectRaw("SUM(month(data_inicio) = 1) AS janeiro, SUM(month(data_inicio) = 2) AS fevereiro, SUM(month(data_inicio) = 3) AS marco, SUM(month(data_inicio) = 4) AS abril, SUM(month(data_inicio) = 5) AS maio, SUM(month(data_inicio) = 6) AS junho, SUM(month(data_inicio) = 7) AS julho, SUM(month(data_inicio) = 8) AS agosto, SUM(month(data_inicio) = 9) AS setembro, SUM(month(data_inicio) = 10) AS outubro, SUM(month(data_inicio) = 11) AS novembro, SUM(month(data_inicio) = 12) AS dezembro, SUM(year(data_inicio)='".$ano."') as total, lista_atividades.atividade")
                                        ->groupBy('atividades_dc.tipo_atividade')
                                        ->orderBy('lista_atividades.atividade')
                                        ->cursor();
                }
                if(count($atividades) === 0){
                    return \back()->with('warning', 'Nenhum resultado encontrado!');
                }

                $dados = array();
                $legenda = array();
                $total = 0;
                foreach($atividades as $item){
                    $dados[] = $item -> quantidade;
                    $legenda[] = $item -> atividade." (".$item -> quantidade.")";
                    $total += $item -> quantidade;
                }
                $graph = new PieGraph(800,500,"auto");
                $graph->img->SetAntiAliasing();
                $graph->SetMarginColor('gray');
                $graph->SetShadow();
        
                // Setup margin and titles
                $graph->title->Set("Atividades referentes ao período entre ".$request -> data_inicial." e ".$request -> data_final);
                $graph->subtitle->Set("Total: $total atividades.");
                $graph->title->SetFont(FF_VERDANA,FS_BOLD,10);
                $graph->title->SetColor("black");
                
                $p1 = new PiePlot3D($dados);
                $p1->SetSize(0.40);
                $p1->SetCenter(0.35);
        
                // Setup slice labels and move them into the plot
                $p1->value->SetFont(FF_VERDANA,FS_BOLD,8);
                $p1->value->SetColor("black");
                $p1->SetLabelPos(1.0);
                
                $p1->SetLegends($legenda);
        
                // Explode all slices
                $p1->ExplodeAll();
        
                $graph->Add($p1);
                $graph->legend->SetPos(0.01,0.2,'right','botton');
                $graph->legend->SetLayout(LEGEND_VERT);
                
                $contentType = 'image/png';
                ob_start();
                $graph->Stroke();
                $image_data = ob_get_clean();
                
                $str = "data:$contentType;base64," . base64_encode($image_data);

                return \view('auth.est_atividades')-> with('atividades', $atividades)
                                                    -> with('grafico1', $str)
                                                    -> with('data_inicial', $request -> data_inicial)
                                                    -> with('data_final', $request -> data_final)
                                                    -> with('orgao', $request -> orgao)
                                                    -> with('nome_orgao', $orgao)
                                                    -> with('anual', $anual);
        
                break;
    
            case '3':
                MtJpGraph::load(['pie', 'pie3d'], true);
                if($request -> orgao == 'todos')
                {
                    $atividades=DB::select(DB::raw("Select ciclo, count(ciclo) as quantidade from atividades_dc where DATE_FORMAT(data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d/m/Y', $request->data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d/m/Y', $request->data_final)->format('Y-m-d')."' group by ciclo order by ciclo"));
		            $orgao = "TODOS";
                    $anual = atividades::whereyear('data_inicio', $ano)
                                        ->selectRaw("SUM(month(data_inicio) = 1) AS janeiro, SUM(month(data_inicio) = 2) AS fevereiro, SUM(month(data_inicio) = 3) AS marco, SUM(month(data_inicio) = 4) AS abril, SUM(month(data_inicio) = 5) AS maio, SUM(month(data_inicio) = 6) AS junho, SUM(month(data_inicio) = 7) AS julho, SUM(month(data_inicio) = 8) AS agosto, SUM(month(data_inicio) = 9) AS setembro, SUM(month(data_inicio) = 10) AS outubro, SUM(month(data_inicio) = 11) AS novembro, SUM(month(data_inicio) = 12) AS dezembro, SUM(year(data_inicio)='".$ano."') as total, ciclo")
                                        ->groupBy('ciclo')
                                        ->orderBy('ciclo')
                                        ->cursor();

                }else
                {
                    $atividades=DB::select(DB::raw("Select ciclo, count(ciclo) as quantidade from atividades_dc where DATE_FORMAT(data_inicio, '%Y-%m-%d') BETWEEN '".Carbon::createFromFormat('d/m/Y', $request->data_inicial)->format('Y-m-d')."' and '".Carbon::createFromFormat('d/m/Y', $request->data_final)->format('Y-m-d')."' and orgao='".$request -> orgao."' group by ciclo order by ciclo"));
                    $orgao = orgao::findorfail($request -> orgao) -> sigla;
                    $anual = atividades::whereyear('data_inicio', $ano)
                                        ->where('orgao', $request -> orgao)
                                        ->selectRaw("SUM(month(data_inicio) = 1) AS janeiro, SUM(month(data_inicio) = 2) AS fevereiro, SUM(month(data_inicio) = 3) AS marco, SUM(month(data_inicio) = 4) AS abril, SUM(month(data_inicio) = 5) AS maio, SUM(month(data_inicio) = 6) AS junho, SUM(month(data_inicio) = 7) AS julho, SUM(month(data_inicio) = 8) AS agosto, SUM(month(data_inicio) = 9) AS setembro, SUM(month(data_inicio) = 10) AS outubro, SUM(month(data_inicio) = 11) AS novembro, SUM(month(data_inicio) = 12) AS dezembro, SUM(year(data_inicio)='".$ano."') as total, ciclo")
                                        ->groupBy('ciclo')
                                        ->orderBy('ciclo')
                                        ->cursor();
                }
                if(count($atividades) === 0){
                    return \back()->with('warning', 'Nenhum resultado encontrado!');
                }

                $dados = array();
                $legenda = array();
                $total = 0;
                foreach($atividades as $item){
                    $dados[] = $item -> quantidade;
                    $legenda[] = $item -> ciclo." (".$item -> quantidade.")";
                    $total += $item -> quantidade;
                }
                $graph = new PieGraph(800,500,"auto");
                $graph->img->SetAntiAliasing();
                $graph->SetMarginColor('gray');
                $graph->SetShadow();
        
                // Setup margin and titles
                $graph->title->Set("Ciclo de atividades referentes ao período entre ".$request -> data_inicial." e ".$request -> data_final);
                $graph->subtitle->Set("Total: $total atividades.");
                $graph->title->SetFont(FF_VERDANA,FS_BOLD,10);
                $graph->title->SetColor("black");
                
                $p1 = new PiePlot3D($dados);
                $p1->SetSize(0.40);
                $p1->SetCenter(0.35);
        
                // Setup slice labels and move them into the plot
                $p1->value->SetFont(FF_VERDANA,FS_BOLD,8);
                $p1->value->SetColor("black");
                $p1->SetLabelPos(1.0);
                
                $p1->SetLegends($legenda);
        
                // Explode all slices
                $p1->ExplodeAll();
        
                $graph->Add($p1);
                $graph->legend->SetPos(0.01,0.2,'right','botton');
                $graph->legend->SetLayout(LEGEND_VERT);
                
                $contentType = 'image/png';
                ob_start();
                $graph->Stroke();
                $image_data = ob_get_clean();
                
                $str = "data:$contentType;base64," . base64_encode($image_data);

                return \view('auth.est_ciclo')-> with('atividades', $atividades)
                                                    -> with('grafico1', $str)
                                                    -> with('data_inicial', $request -> data_inicial)
                                                    -> with('data_final', $request -> data_final)
                                                    -> with('orgao', $request -> orgao)
                                                    -> with('nome_orgao', $orgao)
                                                    -> with('anual', $anual);
        

                break;

        }
    }

    public function export_km($data_inicial, $data_final, $orgao) 
    {
        return Excel::download(new est_quilometros($data_inicial, $data_final, $orgao), 'Quilometros_Rodados.xlsx');
    }

    public function export_atividades($data_inicial, $data_final, $orgao) 
    {
        return Excel::download(new est_atividades($data_inicial, $data_final, $orgao), 'Atividades.xlsx');
    }

    public function export_ciclo($data_inicial, $data_final, $orgao) 
    {
        return Excel::download(new est_ciclo($data_inicial, $data_final, $orgao), 'Ciclo_Atividades.xlsx');
    }

}
