<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\atividades;
use App\Models\ciclo_desastre;
use App\Models\cobrade;
use App\Models\lista_atividades;
use App\Models\op_viatura;
use App\Models\orgao;
use App\Models\redec;
use App\Models\User;
use App\Models\viaturas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

class atv_dcController extends Controller
{
    public function index()
    {
        $rota = 'atv_dc.create';

        $atv = new atividades();
        $atv -> id_atv = 0;
        $atv -> tipo_atividade = "";
        $atv -> ciclo = "";
        $atv -> titulo = "";
        $atv -> orgao = Auth::user() -> orgao;
        $atv -> promotor = null;
        $atv -> autoria = null;
        $atv -> data_inicio = null;
        $atv -> data_fim = null;
        $atv -> municipio = "";
        $atv -> op_viatura = "Não";
        $atv -> fide = null;
        $atv -> ecp = null;
        $atv -> se = null;
        $atv -> cobrade = null;
        $atv -> relatorio_foto = null;
        $atv -> relato = "";
        $atv -> data_cadastro = null;
        $atv -> usuario = Auth::user() -> indice_adm;
        $atv -> num_fide = null;
        $atv -> num_se = null;
        $atv -> num_ecp = null;
        $atv -> arquivos = "";
        $atv -> bol = 'Não';
        $atv -> boletim = null;

        $atvs = atividades::where('orgao', Auth::user() -> orgao) -> orderBy('data_inicio', 'desc')->get();

        $municipios = redec::where('id_orgao', Auth::user() -> orgao)->orderBy('municipio')->get();
        if($municipios -> Count() == 0){
            $municipios = redec::orderBy('municipio')->get();
        }

        $orgaos = orgao::orderBy('sigla')->get();

        $lista_atv = lista_atividades::orderBy('atividade')
                            ->get();

        $lista_vtr = viaturas::where('baixada', 'Não')
                        ->orderBy('prefixo')
                        ->get();

        $viaturas = array();

        $cobrades = cobrade::orderBy('final')-> get();

        $ciclos = ciclo_desastre::where('sigdec', 1)->orderBy('id_ciclo')->get();
        
        return \view('auth.atividades_dc')->with('rota', $rota)
                                        ->with('atvs', $atvs)
                                        ->with('atv', $atv)
                                        ->with('lista_atv', $lista_atv)
                                        ->with('lista_vtr', $lista_vtr)
                                        ->with('municipios', $municipios)
                                        ->with('orgaos', $orgaos)
                                        ->with('viaturas', $viaturas)
                                        ->with('cobrades', $cobrades)
                                        ->with('ciclos', $ciclos);
    }

//******************************************************************************************
    public function create(Request $request)
    {
        $request -> validate([
            'tipo_atividade' => 'required',
            'orgao' => 'required',
            'promotor' => 'sometimes|required',
            'autoria' => 'required',
            'ciclo' => 'required',
            'titulo' => 'required',
            'data_inicio' => 'required',
            'municipio' => 'required|array',
            'op_viatura' => 'required',
            'viatura' => 'sometimes|array|required',
            'kilometragem' => 'sometimes|array|required',
            'bol' => 'required',
            'num_bol' => 'sometimes|required',
            'ecp' => 'required',
            'num_ecp' => 'sometimes|required',
            'se' => 'required',
            'num_se' => 'sometimes|required',
            'fide' => 'required',
            'num_fide' => 'sometimes|required',
            'cobrade' => 'sometimes|required',
            'relatorio_foto' => 'required',
            'relato' => 'required',
        ]);

        $viaturas = [];

        if($request -> op_viatura == 'Sim'){

            foreach ($request -> viatura as $key => $value)
            {
                $viaturas[$key]['vtr'] = $value;
            }

            foreach ($request -> kilometragem as $key => $value)
            {
                $viaturas[$key]['km'] = $value;
            }
        }

        $messages = [];
        if($request->hasfile('fotos')){
            foreach ($request->file('fotos') as $key => $file) {
                $messages['arquivo.'.$key.'.image'] = 'O ' .  $file->getClientOriginalName() . ' deve ser uma imagem.';
                $messages['arquivo.' . $key . '.mimes'] = 'O ' . $file->getClientOriginalName() . ' deve ser um arquivo do tipo : :values.';
            }
            $request->validate([   
                'fotos' => 'required|array',
                'fotos.*' => 'image|mimes:jpg,jpeg,png'
            ], $messages);

            $id_pasta = atividades::orderBy('id_atv', 'desc')->limit(1)->first();

            $pasta = $id_pasta -> id_atv +1;

            $path = 'relatorio_foto/'.$pasta.'_'.date('Y');

            $imagens = "";

            foreach ($request->file('fotos') as $key => $file) {
                $imagem = 'foto_'.time().$key.'.'.$file ->getClientOriginalExtension();
                $imagens .= 'foto_'.time().$key.'.'.$file ->getClientOriginalExtension().'; ';
                $file -> storeAs($path, $imagem);

                $thumbnailpath = public_path('storage/'.$path.'/'.$imagem);
                $img = Image::make($thumbnailpath)->resize(2400, 1200, function($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);
            }
            $imagens = substr($imagens, 0, -2);
        }else
        {
            $imagens = null;
        }

        $teste = atividades::where('data_inicio', Carbon::createFromFormat('d/m/Y H:i:s', $request->data_inicio)->format('Y-m-d H:i:s'))
                                    ->where('orgao', $request -> orgao)
                                    ->where('tipo_atividade', $request -> tipo_atividade)
                                    ->get();

        if($teste -> count() > 0){
            return back() -> with('warning', 'Atividade já cadastrada!')
                          -> withInput($request -> input());
        }

        $municipio = implode(',', $request -> municipio);

        if(isset($request -> promotor)){
            $promotor = $request -> promotor;
        }else{
            $promotor = $request -> orgao;
        }

        if(isset($request -> num_ecp)){
            $num_ecp = $request -> num_ecp;
        }else{
            $num_ecp = null;
        }

        if(isset($request -> num_bol)){
            $num_bol = $request -> num_bol;
        }else{
            $num_bol = null;
        }

        if(isset($request -> num_se)){
            $num_se = $request -> num_se;
        }else{
            $num_se = null;
        }

        if(isset($request -> num_fide)){
            $num_fide = $request -> num_fide;
        }else{
            $num_fide = null;
        }

        if(isset($request -> cobrade)){
            $cobrade = $request -> cobrade;
        }else{
            $cobrade = null;
        }

        if(isset($request -> num_bol)){
            $num_bol = $request -> num_bol;
        }else{
            $num_bol = null;
        }

        $atv = atividades::create([
            'data_inicio' => Carbon::createFromFormat('d/m/Y H:i:s', $request->data_inicio)->format('Y-m-d H:i:s'),
            'data_fim' => ($request->data_fim != '' ? Carbon::createFromFormat('d/m/Y H:i:s', $request->data_fim)->format('Y-m-d H:i:s') : null),
            'orgao' => $request -> orgao,
            'promotor' => $promotor,
            'autoria' => $request -> autoria,
            'tipo_atividade' => $request -> tipo_atividade,
            'ciclo' => $request -> ciclo,
            'titulo' => mb_strtoupper($request -> titulo),
            'municipio' => $municipio,
            'op_viatura' => $request -> op_viatura,
            'bol' => $request -> bol,
            'boletim' => $num_bol,
            'ecp' => $request -> ecp,
            'num_ecp' => $num_ecp,
            'se' => $request -> se,
            'num_se' => $num_se,
            'fide' => $request -> fide,
            'num_fide' => $num_fide,
            'cobrade' => $cobrade,
            'relatorio_foto' => $request -> relatorio_foto,
            'relato' => $request -> relato,
            'data_cadastro' => date('Y-m-d'),
            'arquivos' => $imagens,
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($atv){

            if(count($viaturas) > 0){
                foreach($viaturas as $item){
                    op_viatura::create([
                        'id_viatura' => $item['vtr'],
                        'id_atv' => $atv -> id_atv,
                        'kilometragem' => $item['km'],
                    ]);
                }
            }

            $sigdec = DB::select(DB::raw("Select distinct og.sigla as Órgão, ad.data_inicio as Início, ad.data_fim as Término, ad.ciclo as Ciclo, la.atividade as Atividade, la.descricao as Dica, ad.municipio as Municípios, ad.autoria as Participação, ad.id_atv as ID, ad.op_viatura as VTR, vt.prefixo as Viatura, vtr.kilometragem as KM From atividades_dc ad left join orgao og on ad.orgao=og.id_orgao left join lista_atividades la on ad.tipo_atividade=la.id_atividade left join op_viatura vtr on ad.id_atv=vtr.id_atv left join viaturas vt on vt.id_viatura=vtr.id_viatura where ad.data_fim is not null order by ad.data_inicio"));
            $output = fopen('sigdec.csv', 'w');
            $header = true;
            foreach($sigdec as $item){
    
                if ($header) {
                    fputcsv($output, array_keys(\mb_convert_encoding((array) $item, "ISO-8859-1")));
                    $header = false;
                }
                fputcsv($output, \mb_convert_encoding((array) $item, "ISO-8859-1"));
            }
            fclose($output);
    
            $subject = "Cadastro de Atividade - ".Auth::user() -> Orgao['sigla'];
            $mensagem = "O usuário".Auth::user() -> nome." cadastrou uma atividade.<br>";
            $mensagem .= "Órgão: ".$atv -> Orgao['sigla']."<br>";
            $mensagem .= "Órgão Promotor: ".$atv -> Promotor['sigla']."<br>";
            $mensagem .= "Tipo de Atividade: ".$atv -> TipoAtividade['atividade']."<br>";
            $mensagem .= "titulo: ".$atv -> titulo."<br>";
            $mensagem .= "Ciclo: ".$atv -> ciclo."<br>";
            $mensagem .= "Município(s): ".$municipio."<br>";
            $mensagem .= "Utilização de Viatura: ".$atv -> op_viatura."<br>";
            $mensagem .= "ECP: ".$atv -> ecp."<br>";
            if($atv -> bol =="Sim"){
                $mensagem .= "Boletim SEDEC: ".$atv -> boletim."<br>";
            }
            if($atv -> ecp =="Sim"){
                $mensagem .= "Número ECP: ".$atv -> num_ecp."<br>";
            }
            $mensagem .= "SE: ".$atv -> se."<br>";
            if($atv -> se =="Sim"){
                $mensagem .= "Número SE: ".$atv -> num_se."<br>";
            }
            $mensagem .= "FIDE: ".$atv -> fide."<br>";
            if($atv -> fide =="Sim"){
                $mensagem .= "Número FIDE: ".$atv -> num_fide."<br>";
                $mensagem .= "COBRADE: ".$atv -> cobrade."<br>";
            }
            $mensagem .= "Relatório Fotográfico: ".$atv -> relatorio_foto."<br>";
            $mensagem .= "Data de Início: ".$request -> data_inicio."<br>";
            $mensagem .= "Data de Término: ".$request -> data_fim."<br>";
            $mensagem .= "Data do cadastro: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Atividade cadastrada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Atividade cadastrada, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao cadastrar atividade!'])
                          -> withInput($request ->input());
        }
    }
//******************************************************************************************
    public function edit($id)
    {
        $atv = atividades::findOrFail($id);
        $rota = 'atv_dc.update';

        $atvs = atividades::where('orgao', Auth::user() -> orgao)->orderBy('data_inicio', 'desc')->get();

        $lista_atv = lista_atividades::orderBy('atividade')
                            ->get();

        $lista_vtr = viaturas::where('baixada', 'Não')
                            ->orderBy('prefixo')
                            ->get();

        $viaturas = op_viatura::where('id_atv', $id)
                            -> where('ativo', 'Sim')
                            -> get();

        $cobrades = cobrade::orderBy('final')-> get();
                    
        $municipios = redec::where('id_orgao', Auth::user() -> orgao)->orderBy('municipio')->get();
        if($municipios -> Count() == 0){
            $municipios = redec::orderBy('municipio')->get();
        }

        $orgaos = orgao::orderBy('sigla')->get();
                    
        $ciclos = ciclo_desastre::orderBy('id_ciclo')->get();
        
        return \view('auth.atividades_dc')->with('rota', $rota)
                                        ->with('atvs', $atvs)
                                        ->with('atv', $atv)
                                        ->with('lista_atv', $lista_atv)
                                        ->with('lista_vtr', $lista_vtr)
                                        ->with('municipios', $municipios)
                                        ->with('orgaos', $orgaos)
                                        ->with('ciclos', $ciclos)
                                        ->with('cobrades', $cobrades)
                                        ->with('viaturas', $viaturas);
    }
//******************************************************************************************

    public function update(Request $request, $id)
    {
        $atv = atividades::findOrFail($id);

        $request -> validate([
            'tipo_atividade' => 'required',
            'orgao' => 'required',
            'promotor' => 'sometimes|required',
            'autoria' => 'required',
            'ciclo' => 'required',
            'titulo' => 'required',
            'data_inicio' => 'required',
            'municipio' => 'required|array',
            'op_viatura' => 'required',
            'viatura' => 'sometimes|array|required',
            'kilometragem' => 'sometimes|array|required',
            'bol' => 'required',
            'num_bol' => 'sometimes|required',
            'ecp' => 'required',
            'num_ecp' => 'sometimes|required',
            'se' => 'required',
            'num_se' => 'sometimes|required',
            'fide' => 'required',
            'num_fide' => 'sometimes|required',
            'cobrade' => 'sometimes|required',
            'relatorio_foto' => 'required',
            'relato' => 'required',
        ]);

        $viaturas = [];

        if($request -> op_viatura == 'Sim'){

            foreach ($request -> viatura as $key => $value)
            {
                $viaturas[$key]['vtr'] = $value;
            }

            foreach ($request -> kilometragem as $key => $value)
            {
                $viaturas[$key]['km'] = $value;
            }
        }

        $messages = [];
        if($request->hasfile('fotos')){
            foreach ($request->file('fotos') as $key => $file) {
                $messages['arquivo.'.$key.'.image'] = 'O ' .  $file->getClientOriginalName() . ' deve ser uma imagem.';
                $messages['arquivo.' . $key . '.mimes'] = 'O ' . $file->getClientOriginalName() . ' deve ser um arquivo do tipo : :values.';
            }
            $request->validate([   
                'fotos' => 'required|array',
                'fotos.*' => 'image|mimes:jpg,jpeg,png'
            ], $messages);

            $path = 'relatorio_foto/'.$atv -> id_atv.'_'.substr($atv -> data_cadastro, 0, 4);

            $imagens = "";

            foreach ($request->file('fotos') as $key => $file) {
                $imagem = 'foto_'.time().$key.'.'.$file ->getClientOriginalExtension();
                $imagens .= 'foto_'.time().$key.'.'.$file ->getClientOriginalExtension().'; ';
                $file ->storeAs($path, $imagem);

                $thumbnailpath = public_path('storage/'.$path.'/'.$imagem);
                $img = Image::make($thumbnailpath)->resize(2400, 1200, function($constraint) {
                    $constraint->aspectRatio();
                });
                $img->save($thumbnailpath);
            }
            if($atv -> arquivos != ""){
                $imagens .= $atv -> arquivos;
            }else{
                $imagens = substr($imagens, 0, -2);
            }
        }else
        {
            $imagens = $atv -> arquivos;
        }

        $municipio = implode(',', $request -> municipio);

        if(isset($request -> promotor)){
            $promotor = $request -> promotor;
        }else{
            $promotor = $request -> orgao;
        }

        if(isset($request -> num_ecp)){
            $num_ecp = $request -> num_ecp;
        }else{
            $num_ecp = null;
        }

        if(isset($request -> num_bol)){
            $num_bol = $request -> num_bol;
        }else{
            $num_bol = null;
        }

        if(isset($request -> num_se)){
            $num_se = $request -> num_se;
        }else{
            $num_se = null;
        }

        if(isset($request -> num_fide)){
            $num_fide = $request -> num_fide;
        }else{
            $num_fide = null;
        }

        if(isset($request -> cobrade)){
            $cobrade = $request -> cobrade;
        }else{
            $cobrade = null;
        }

        if(isset($request -> num_bol)){
            $num_bol = $request -> num_bol;
        }else{
            $num_bol = null;
        }

        $update = $atv -> update([
            'data_inicio' => Carbon::createFromFormat('d/m/Y H:i:s', $request->data_inicio)->format('Y-m-d H:i:s'),
            'data_fim' => ($request->data_fim != '' ? Carbon::createFromFormat('d/m/Y H:i:s', $request->data_fim)->format('Y-m-d H:i:s') : null),
            'orgao' => $request -> orgao,
            'promotor' => $promotor,
            'autoria' => $request -> autoria,
            'tipo_atividade' => $request -> tipo_atividade,
            'ciclo' => $request -> ciclo,
            'titulo' => mb_strtoupper($request -> titulo),
            'municipio' => $municipio,
            'op_viatura' => $request -> op_viatura,
            'bol' => $request -> bol,
            'boletim' => $num_bol,
            'ecp' => $request -> ecp,
            'num_ecp' => $num_ecp,
            'se' => $request -> se,
            'num_se' => $num_se,
            'fide' => $request -> fide,
            'num_fide' => $num_fide,
            'cobrade' => $cobrade,
            'relatorio_foto' => $request -> relatorio_foto,
            'relato' => $request -> relato,
            'data_cadastro' => date('Y-m-d'),
            'arquivos' => $imagens,
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($update){

            $vtr = op_viatura::where('id_atv', $id)
                            ->where('ativo', 'Sim');

            if(count($viaturas) > 0){
                if($vtr -> count() > 0){
                    $vtr -> update([
                        'ativo' => 'Não',
                    ]);
                    foreach($viaturas as $item){
                        op_viatura::create([
                            'id_viatura' => $item['vtr'],
                            'id_atv' => $atv -> id_atv,
                            'kilometragem' => $item['km'],
                        ]);
                    }
                }
            }

            $vtr = op_viatura::where('ativo', 'Não')->delete();

            $sigdec = DB::select(DB::raw("Select distinct og.sigla as Órgão, ad.data_inicio as Início, ad.data_fim as Término, ad.ciclo as Ciclo, la.atividade as Atividade, la.descricao as Dica, ad.municipio as Municípios, ad.autoria as Participação, ad.id_atv as ID, ad.op_viatura as VTR, vt.prefixo as Viatura, vtr.kilometragem as KM From atividades_dc ad left join orgao og on ad.orgao=og.id_orgao left join lista_atividades la on ad.tipo_atividade=la.id_atividade left join op_viatura vtr on ad.id_atv=vtr.id_atv left join viaturas vt on vt.id_viatura=vtr.id_viatura where ad.data_fim is not null order by ad.data_inicio"));
            $output = fopen('sigdec.csv', 'w');
            $header = true;
            foreach($sigdec as $item){
    
                if ($header) {
                    fputcsv($output, array_keys(\mb_convert_encoding((array) $item, "ISO-8859-1")));
                    $header = false;
                }
                fputcsv($output, \mb_convert_encoding((array) $item, "ISO-8859-1"));
            }
            fclose($output);

            $subject = "Edição de Atividade - ".Auth::user() -> Orgao['sigla'];
            $mensagem = "O usuário".Auth::user() -> nome." cadastrou uma atividade.<br>";
            $mensagem .= "Órgão: ".$atv -> Orgao['sigla']."<br>";
            $mensagem .= "Órgão Promotor: ".$atv -> Promotor['sigla']."<br>";
            $mensagem .= "Tipo de Atividade: ".$atv -> TipoAtividade['atividade']."<br>";
            $mensagem .= "titulo: ".$atv -> titulo."<br>";
            $mensagem .= "Ciclo: ".$atv -> ciclo."<br>";
            $mensagem .= "Município(s): ".$municipio."<br>";
            $mensagem .= "Utilização de Viatura: ".$atv -> op_viatura."<br>";
            $mensagem .= "ECP: ".$atv -> ecp."<br>";
            if($atv -> bol =="Sim"){
                $mensagem .= "Boletim SEDEC: ".$atv -> boletim."<br>";
            }
            if($atv -> ecp =="Sim"){
                $mensagem .= "Número ECP: ".$atv -> num_ecp."<br>";
            }
            $mensagem .= "SE: ".$atv -> se."<br>";
            if($atv -> se =="Sim"){
                $mensagem .= "Número SE: ".$atv -> num_se."<br>";
            }
            $mensagem .= "FIDE: ".$atv -> fide."<br>";
            if($atv -> fide =="Sim"){
                $mensagem .= "Número FIDE: ".$atv -> num_fide."<br>";
                $mensagem .= "COBRADE: ".$atv -> cobrade."<br>";
            }
            $mensagem .= "Relatório Fotográfico: ".$atv -> relatorio_foto."<br>";
            $mensagem .= "Data de Início: ".$request -> data_inicio."<br>";
            $mensagem .= "Data de Término: ".$request -> data_fim."<br>";
            $mensagem .= "Data da edição: ".date("d/m/Y")."<br>";

            $rota = 'atv_dc.create';

            $atv = new atividades();
            $atv -> id_atv = 0;
            $atv -> tipo_atividade = "";
            $atv -> ciclo = "";
            $atv -> titulo = "";
            $atv -> orgao = Auth::user() -> orgao;
            $atv -> promotor = null;
            $atv -> autoria = null;
            $atv -> data_inicio = null;
            $atv -> data_fim = null;
            $atv -> municipio = "";
            $atv -> op_viatura = "Não";
            $atv -> fide = null;
            $atv -> ecp = null;
            $atv -> se = null;
            $atv -> cobrade = null;
            $atv -> relatorio_foto = null;
            $atv -> relato = "";
            $atv -> data_cadastro = null;
            $atv -> usuario = Auth::user() -> indice_adm;
            $atv -> num_fide = null;
            $atv -> num_se = null;
            $atv -> num_ecp = null;
            $atv -> arquivos = "";
            $atv -> bol = 'Não';
            $atv -> boletim = null;
    
            $atvs = atividades::where('orgao', Auth::user() -> orgao) -> orderBy('data_inicio', 'desc')->get();
    
            $municipios = redec::where('id_orgao', Auth::user() -> orgao)->orderBy('municipio')->get();
            if($municipios -> Count() == 0){
                $municipios = redec::orderBy('municipio')->get();
            }
    
            $orgaos = orgao::orderBy('sigla')->get();
    
            $lista_atv = lista_atividades::orderBy('atividade')
                                ->get();
    
            $lista_vtr = viaturas::where('baixada', 'Não')
                            ->orderBy('prefixo')
                            ->get();
    
            $viaturas = array();
    
            $cobrades = cobrade::orderBy('final')-> get();
    
            $ciclos = ciclo_desastre::where('sigdec', 1)->orderBy('id_ciclo')->get();
            
            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return \redirect()->route('atv_dc.index') -> with(['success' => 'Atividade editada com sucesso!'])
                                                            ->with('rota', $rota)
                                                            ->with('atvs', $atvs)
                                                            ->with('atv', $atv)
                                                            ->with('lista_atv', $lista_atv)
                                                            ->with('lista_vtr', $lista_vtr)
                                                            ->with('municipios', $municipios)
                                                            ->with('orgaos', $orgaos)
                                                            ->with('viaturas', $viaturas)
                                                            ->with('cobrades', $cobrades)
                                                            ->with('ciclos', $ciclos);
            } 
            catch (\Throwable $th) {
                return redirect()->route('atv_dc.index') -> with(['warning' => 'Edição concluída, mas houve um problema no envio da mensagem de retorno!'])
                                                            ->with('rota', $rota)
                                                            ->with('atvs', $atvs)
                                                            ->with('atv', $atv)
                                                            ->with('lista_atv', $lista_atv)
                                                            ->with('lista_vtr', $lista_vtr)
                                                            ->with('municipios', $municipios)
                                                            ->with('orgaos', $orgaos)
                                                            ->with('viaturas', $viaturas)
                                                            ->with('cobrades', $cobrades)
                                                            ->with('ciclos', $ciclos);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao editar atividade!'])
                          -> withInput($request ->input());
        }
    }
//******************************************************************************************
    public function download($id)
    {
        $atv = atividades::findOrFail($id);
        $viaturas = op_viatura::where('id_atv', $id)->where('ativo', 'Sim')->get();

        $pdf = PDF::loadView('auth.atividade_dc',['atv' => $atv, 'viaturas' => $viaturas]); 
        $options = $pdf->getOptions();
        $options->set('isPhpEnabled', true);
        $pdf->setPaper('A4', 'portrait');
        $pdf->getDOMPdf()->setOptions($options);            
        return $pdf->stream('Atividade_Interna.pdf');

    }
//******************************************************************************************
    public function fotos($id)
    {
        $atv = atividades::findOrFail($id);
        
        return \view('auth.relatorio_fotografico')->with('atv', $atv);
    }
//******************************************************************************************
    public function delete($id, $img)
    {
        $atv = atividades::findOrFail($id);

        $path = 'storage/relatorio_foto/'.$atv -> id_atv.'_'.substr($atv -> data_cadastro, 0,4).'/';

        if (preg_match('/'.$img.'; '.'/', $atv -> arquivos)){
            $arquivos = str_replace($img.'; ',"", $atv -> arquivos);
        }else{
            if (preg_match('/'.'; '.$img.'/', $atv -> arquivos)){
                $arquivos = str_replace('; '.$img,"", $atv -> arquivos);
            }else{
                if (preg_match('/'.$img.'/', $atv -> arquivos)){
                    $arquivos = str_replace($img,"", $atv -> arquivos);
                }
            }
        }
    
        $del = $atv -> update([
            'arquivos' => $arquivos,
        ]);

        if($del){
            \unlink(\public_path($path.$img));

            return back() -> with(['success' => 'exclusão de imagem realizada com sucesso!']);

        }else{
            return back() -> with(['error' => 'Erro ao excluir imagem!']);
        }

    }

}
