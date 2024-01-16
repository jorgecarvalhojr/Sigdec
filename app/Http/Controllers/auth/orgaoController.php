<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\orgao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class orgaoController extends Controller
{
    public function index()
    {
        $rota = 'orgao.create';
        $orgao = new orgao();
        $orgao -> id_orgao = 0;
        $orgao -> sigla = "";
        $orgao -> descricao = "";
        
        $estrutura = orgao::orderBy('sigla')->get();
        
        return \view('auth.orgao')->with('rota', $rota)
                                        ->with('orgao', $orgao)
                                        ->with('estrutura', $estrutura);
    }

    /*******************************************************************************/
    public function create(Request $request)
    {
        $request -> validate([
            'sigla' => 'required',
            'descricao' => 'required',
        ]);

        $teste = orgao::where('sigla', $request -> sigla);

        if($teste -> count() > 0){
            return back() -> with('warning', 'Órgão já cadastrado!')
                          -> withInput($request -> input());
        }

        $estrutura = orgao::create([
            'sigla' => \mb_strtoupper($request -> sigla),
            'descricao' => $request -> descricao,
        ]);

        if($estrutura){
            $subject = "Cadastro de Órgão da SEDEC";
            $mensagem = "O usuário".Auth::user() -> nome." cadastrou um órgão.<br>";
            $mensagem .= "Sigla: ".$request ->sigla."<br>";
            $mensagem .= "Descrição: ".$request ->descricao."<br>";
            $mensagem .= "Data do cadastro: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Órgão cadastrado com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao criar órgão!'])
                          -> withInput($request ->input());
        }
    }

    /********************************************************************************************/
    public function edit($id)
    {
        $orgao = orgao::findOrFail($id);

        $rota = 'orgao.update';
        $estrutura = orgao::orderBy('sigla')->get();

        return \view('auth.orgao')->with('rota', $rota)
                                        ->with('orgao', $orgao)
                                        ->with('estrutura', $estrutura);
    }

    /*********************************************************************************************/
    public function update(Request $request, $id)
    {
        $estrutura = orgao::findOrFail($id);

        $request -> validate([
            'sigla' => 'required',
            'descricao' => 'required',
        ]);

        if($request-> sigla != $estrutura -> sigla){
            $teste = orgao::where('sigla', $request -> sigla);

            if($teste -> count() > 0){
                return back() -> with('warning', 'Órgão já cadastrado!')
                            -> withInput($request -> input());
            }
        }

        $update = $estrutura -> update([
            'sigla' => \mb_strtoupper($request -> sigla),
            'descricao' => $request -> descricao,
        ]);

        if($update){
            $subject = "Edição de Órgão da SEDEC";
            $mensagem = "O usuário".Auth::user() -> nome." editou um órgão.<br>";
            $mensagem .= "Sigla: ".$request ->sigla."<br>";
            $mensagem .= "Descrição: ".$request ->descricao."<br>";
            $mensagem .= "Data da edição: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return redirect() -> route('orgao.index')-> with(['success' => 'Estrutura editada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return redirect() -> route('orgao.index')-> with(['warning' => 'Houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao editar estrutura!'])
                          -> withInput($request ->input());
        }
    }
}
