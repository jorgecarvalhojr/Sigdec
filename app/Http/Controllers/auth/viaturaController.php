<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\orgao;
use App\Models\viaturas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class viaturaController extends Controller
{
    public function index()
    {
        $rota = 'viatura.create';
        $viatura = new viaturas();
        $viatura -> id_viatura = 0;
        $viatura -> prefixo = "";
        $viatura -> tipo = "";
        $viatura -> placa = "";
        $viatura -> orgao_carga = "";
        $viatura -> orgao_utilizado = "";
        $viatura -> baixada = "Não";

        $orgaos = orgao::orderBy('sigla')->get();

        $viaturas = viaturas::orderBy('prefixo')
                            ->orderBy('tipo')
                            ->get();
        
        return \view('auth.viatura')->with('rota', $rota)
                                        ->with('viatura', $viatura)
                                        ->with('orgaos', $orgaos)
                                        ->with('viaturas', $viaturas);
    }
//******************************************************************************************
    public function create(Request $request)
    {
        $request -> validate([
            'prefixo' => 'required',
            'tipo' => 'required',
            'placa' => 'required',
            'baixada' => 'required',
            'orgao_carga' => 'required',
            'orgao_utilizado' => 'required',
        ]);

        $teste = viaturas::where('placa', $request -> placa);

        if($teste -> count() > 0){
            return back() -> with('warning', 'Viatura já cadastrada!')
                          -> withInput($request -> input());
        }

        $viatura = viaturas::create([
            'prefixo' => \mb_strtoupper($request -> prefixo),
            'placa' => \mb_strtoupper($request -> placa),
            'tipo' => $request -> tipo,
            'baixada' => $request -> baixada,
            'orgao_carga' => $request -> orgao_carga,
            'orgao_utilizado' => $request -> orgao_utilizado,
            'data_cadastro' => date('Y-m-d'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($viatura){
            $subject = "Cadastro de Viatura";
            $mensagem = "O usuário".Auth::user() -> nome." cadastrou uma viatura.<br>";
            $mensagem .= "Prefixo: ".$viatura ->prefixo."<br>";
            $mensagem .= "Tipo: ".$viatura ->tipo."<br>";
            $mensagem .= "Placa: ".$viatura ->placa."<br>";
            $mensagem .= "Órgão Patriminiado: ".$viatura ->orgao_carga."<br>";
            $mensagem .= "Órgão que utiliza: ".$viatura ->orgao_utilizado."<br>";
            $mensagem .= "Baixada: ".$viatura ->baixada."<br>";
            $mensagem .= "Data do cadastro: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Viatura cadastrada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Viatura cadastrada, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao cadastrar viatura!'])
                          -> withInput($request ->input());
        }
    }
//******************************************************************************************

    public function edit($id)
    {
        $viatura = viaturas::findOrFail($id);

        $rota = 'viatura.update';
        $viaturas = viaturas::orderBy('prefixo')->orderBy('tipo')->get();
        $orgaos = orgao::orderBy('sigla')->get();

        return \view('auth.viatura')->with('rota', $rota)
                                        ->with('viatura', $viatura)
                                        ->with('orgaos', $orgaos)
                                        ->with('viaturas', $viaturas);
    }
//******************************************************************************************

    public function update(Request $request, $id)
    {
        $viatura = viaturas::findOrFail($id);

        $request -> validate([
            'prefixo' => 'required',
            'tipo' => 'required',
            'placa' => 'required',
            'baixada' => 'required',
            'orgao_carga' => 'required',
            'orgao_utilizado' => 'required',
        ]);

        if($request-> placa != $viatura -> placa){
            $teste = viaturas::where('placa', $request -> placa);

            if($teste -> count() > 0){
                return back() -> with('warning', 'Viatura já cadastrada!')
                            -> withInput($request -> input());
            }
        }

        $update = $viatura -> update([
            'prefixo' => \mb_strtoupper($request -> prefixo),
            'placa' => \mb_strtoupper($request -> placa),
            'tipo' => $request -> tipo,
            'baixada' => $request -> baixada,
            'orgao_carga' => $request -> orgao_carga,
            'orgao_utilizado' => $request -> orgao_utilizado,
            'data_cadastro' => date('Y-m-d'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($update){
            $subject = "Edição de Viatura";
            $mensagem = "O usuário".Auth::user() -> nome." editou uma viatura.<br>";
            $mensagem .= "Prefixo: ".$viatura ->prefixo."<br>";
            $mensagem .= "Tipo: ".$viatura ->tipo."<br>";
            $mensagem .= "Placa: ".$viatura ->placa."<br>";
            $mensagem .= "Órgão Patriminiado: ".$viatura ->orgao_carga."<br>";
            $mensagem .= "Órgão que utiliza: ".$viatura ->orgao_utilizado."<br>";
            $mensagem .= "Baixada: ".$viatura ->baixada."<br>";
            $mensagem .= "Data da edição: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return \redirect()->route('viatura.index') -> with(['success' => 'Viatura editada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return redirect()->route('viatura.index') -> with(['warning' => 'Edição concluída, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao editar viatura!'])
                          -> withInput($request ->input());
        }
    }
}
