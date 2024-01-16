<?php

namespace App\Http\Controllers\auth\diagnose;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\diagnose\edicao;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class edicoesController extends Controller
{
    public function index()
    {
        $rota = 'd.edicao.create';

        $edicao = new edicao();
        $edicao -> id = 0;
        $edicao -> ano = '';
        $edicao -> data_inicio = '';
        $edicao -> data_fim = '';
        $edicao -> ativo = '';

        $edicoes = edicao::orderBy('ano', 'desc')->cursor();
        
        return view('auth.diagnose.edicoes')->with('edicao', $edicao)
                                           ->with('edicoes', $edicoes)
                                           ->with('rota', $rota);
    }
//********************************************************************************** */
    public function edit($id)
    {
        $rota = 'd.edicao.edit';

        $edicao = edicao::findOrFail($id);

        $edicoes = edicao::orderBy('ano', 'desc')->cursor();
        
        return view('auth.diagnose.edicoes')->with('edicao', $edicao)
                                           ->with('edicoes', $edicoes)
                                           ->with('rota', $rota);
    }
//********************************************************************************** */
    public function create(Request $request)
    {
        $request -> validate([
            'ano' => 'required',
            'data_inicio' => 'required',
            'ativo' => 'required',
        ]);

        $teste = edicao::where('ano', $request -> ano);

        if($teste -> count() > 0){
            return back() -> with('warning', 'Edição já cadastrada!')
                          -> withInput($request -> input());
        }

        $edicao = edicao::create([
            'ano' => $request -> ano,
            'data_inicio' => Carbon::createFromFormat('d/m/Y', $request->data_inicio)->format('Y-m-d'),
            'data_fim' => ($request->data_fim != '' ? Carbon::createFromFormat('d/m/Y', $request->data_fim)->format('Y-m-d') : null),
            'ativo' => $request -> ativo,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($edicao){
            $subject = "Cadastro de edição de Relatório Diagnose";
            $mensagem = "O usuário ".Auth::user() -> nome." cadastrou um edição de R.D.<br>";
            $mensagem .= "Ano: ".$edicao ->ano."<br>";
            $mensagem .= "Data de Início: ".$request ->data_inicio."<br>";
            $mensagem .= "Data de Término: ".($request ->data_fim != '' ? $request -> data_fim : 'Não especificado')."<br>";
            $mensagem .= "Ativo: ".($edicao ->ativo == '1' ? 'Sim' : ($edicao -> ativo == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Data do cadastro: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Edição cadastrada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Edição cadastrada, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao cadastrar edição!'])
                          -> withInput($request ->input());
        }
    }
//******************************************************************************************

    public function update(Request $request, $id)
    {
        $edicao = edicao::findOrFail($id);

        $request -> validate([
            'ano' => 'required',
            'data_inicio' => 'required',
            'ativo' => 'required',
        ]);

        if($request-> ano != $edicao -> ano){
            $teste = edicao::where('ano', $request -> ano);

            if($teste -> count() > 0){
                return back() -> with('warning', 'Edição já cadastrada!')
                            -> withInput($request -> input());
            }
        }

        $update = $edicao -> update([
            'ano' => $request -> ano,
            'data_inicio' => Carbon::createFromFormat('d/m/Y', $request->data_inicio)->format('Y-m-d'),
            'data_fim' => ($request->data_fim != '' ? Carbon::createFromFormat('d/m/Y', $request->data_fim)->format('Y-m-d') : null),
            'ativo' => $request -> ativo,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($update){
            $subject = "Edição de edição de Relatório Diagnose";
            $mensagem = "O usuário ".Auth::user() -> nome." editou um edição de R.D.<br>";
            $mensagem .= "Ano: ".$edicao ->ano."<br>";
            $mensagem .= "Data de Início: ".$request ->data_inicio."<br>";
            $mensagem .= "Data de Término: ".($request ->data_fim != '' ? $request -> data_fim : 'Não especificado')."<br>";
            $mensagem .= "Ativo: ".($edicao ->ativo == '1' ? 'Sim' : ($edicao -> ativo == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Data da edição: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return \redirect()->route('d.edicao.index') -> with(['success' => 'Registro editado com sucesso!']);

            } 
            catch (\Throwable $th) {
                return redirect()->route('d.edicao.index') -> with(['warning' => 'Edição concluída, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao editar registro!'])
                          -> withInput($request ->input());
        }
    }
}
