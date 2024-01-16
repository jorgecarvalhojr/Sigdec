<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\configuracao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ConfigController extends Controller
{
        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $config = configuracao::where('orgao', Auth::user() -> orgao)
                                ->first();
        
        if($config){
            $rota = "config.update";
        }else{
            $config = new configuracao();
            $config -> orgao = Auth::user()-> orgao;
            $config -> fundo = "F5703C";
            $config -> fonte = "000080";
            $config -> logo1 = "";
            $config -> logo2 = "";
            $config -> titulo1 = "";
            $config -> titulo2 = "";
            $config -> titular = "";
            $config -> funcao_titular = "";
            $config -> mat_titular = "";
            $config -> endereco = "";
            $config -> site = "";
            $config -> telefone1 = "";
            $config -> telefone2 = "";
            $config -> email = "";

            $rota = "config.create";
        }

        return view('auth.config')->with(['config' => $config])
                                  ->with(['rota' => $rota]);
    }

    /*****************************************************************************************/
    public function create(Request $request)
    {
        $orgao = Auth::user()-> orgao;
        $request -> validate([

            'fundo' => 'required',
            'fonte' => 'required',
            'titulo1' => 'required',
            'titulo2' => 'required',
            'titular' => 'required',
            'funcao_titular' => 'required',
            'mat_titular' => 'required',
            'endereco' => 'required',
            'telefone1' => 'required|telefone_com_ddd',
            'telefone2' => 'telefone_com_ddd',
            'site' => 'required',
            'email' => 'required|email',
            'logo1' => 'image|mimes:png|max:2048',
            'logo2' => 'image|mimes:png|max:2048',
        ]); 

        $path="logos/".Auth::user() -> Orgao['sigla'];

        if ($request->hasFile('logo1')) {
            $logo1 = "logo1.".$request->logo1->getClientOriginalExtension();
            $request->logo1->storeAs($path, $logo1);
            $logo1_path = $path."/".$logo1;
        }else{
            $logo1_path = "logos/logo1.png";
        }
  
        if ($request->hasFile('logo2')) {
            $logo2 = "logo2.".$request->logo2->getClientOriginalExtension();
            $request->logo2->storeAs($path, $logo2);
            $logo2_path = $path."/".$logo2;
        }else{
            $logo2_path = "logos/logo2.png";
        }
  
        $config = configuracao::create([
            'fundo' => $request->fundo,
            'fonte' => $request->fonte,
            'titulo1' => mb_strtoupper($request-> titulo1),
            'titulo2' => mb_strtoupper($request -> titulo2),
            'titular' => $request -> titular,
            'orgao' => $orgao,
            'funcao_titular' => $request -> funcao_titular,
            'mat_titular' => $request -> mat_titular,
            'endereco' => $request -> endereco,
            'telefone1' => $request -> telefone1,
            'telefone2' => $request -> telefone2,
            'site' => $request -> site,
            'email' => $request -> email,
            'logo1' => $logo1_path,
            'logo2' => $logo2_path,
            'data' => date("Y-m-d"),
            'usuario' => Auth::user()->indice_adm,
        ]);
        $subject = 'Configuração do SIGDEC - '.$config -> Orgao['sigla'];

        if(!$config){
            return back() -> with('error', 'Erro ao gravar Configuração!')
                          -> withInput($request ->input());
        }else{
            $mensagem  = 'O Usuário: '.Auth::user()->nome.' realizou configuração do SIGDEC.<br>';
            $mensagem .= 'Fonte: '.$request -> fonte.'<br>';
            $mensagem .= 'Fundo: '.$request -> fundo.'<br>';
            $mensagem .= 'Título 1: '.$request -> titulo1.'<br>';
            $mensagem .= 'Título 2: '.$request -> titulo2.'<br>';
            $mensagem .= 'Logo 1: '.$logo1_path.'<br>';
            $mensagem .= 'Logo 2: '.$logo2_path.'<br>';
            $mensagem .= 'Orgão: '.$config -> Orgao['sigla'].'<br>';
            $mensagem .= 'Titular: '.$request -> titular.'<br>';
            $mensagem .= 'Titular/Função: '.$request -> funcao_titular.'<br>';
            $mensagem .= 'Titular/Matrícula: '.$request -> mat_titular.'<br>';
            $mensagem .= 'Endereço: '.$request -> endereco.'<br>';
            $mensagem .= 'Telefone 1: '.$request -> telefone1.'<br>';
            $mensagem .= 'Telefone 2: '.$request -> telefone2.'<br>';
            $mensagem .= 'site: '.$request -> site.'<br>';
            $mensagem .= 'E-mail: '.$request -> email.'<br>';
            $mensagem .= 'Data do Cadastro: '.date("d/m/Y").'<br>';

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user()->email,
            ];
            try {
                Mail::to(Auth::user()->email)
                ->send( new SendMail($data));
                return back()->with('success', 'Configuração realizada com sucesso!');

            } catch (\Throwable $th) {
                return back()->with('warning', 'Não foi possível enviar confirmação por E-mail!');
            }    
        }
    }

    /****************************************************************************************/

    public function update(Request $request, $id)
    {
        $registro = configuracao::findOrFail($id);

        $request -> validate([

            'fundo' => 'required',
            'fonte' => 'required',
            'titulo1' => 'required',
            'titulo2' => 'required',
            'titular' => 'required',
            'funcao_titular' => 'required',
            'mat_titular' => 'required',
            'endereco' => 'required',
            'telefone1' => 'required|telefone_com_ddd',
            'telefone2' => 'telefone_com_ddd',
            'site' => 'required',
            'email' => 'required|email',
            'logo1' => 'image|mimes:png|max:2048',
            'logo2' => 'image|mimes:png|max:2048',
        ]); 

        $path="logos/".Auth::user() -> Orgao['sigla'];

        if ($request->hasFile('logo1')) {
            $logo1 = "logo1.".$request->logo1->getClientOriginalExtension();
            $request->logo1->storeAs($path, $logo1);
            $logo1_path = $path."/".$logo1;
        }else{
            $logo1_path = $registro -> logo1;
        }
  
        if ($request->hasFile('logo2')) {
            $logo2 = "logo2.".$request->logo2->getClientOriginalExtension();
            $request->logo2->storeAs($path, $logo2);
            $logo2_path = $path."/".$logo2;
        }else{
            $logo2_path = $registro -> logo2;
        }
  
        $config = $registro -> update([
            'fundo' => $request->fundo,
            'fonte' => $request->fonte,
            'titulo1' => mb_strtoupper($request-> titulo1),
            'titulo2' => mb_strtoupper($request -> titulo2),
            'titular' => $request -> titular,
            'funcao_titular' => $request -> funcao_titular,
            'mat_titular' => $request -> mat_titular,
            'endereco' => $request -> endereco,
            'telefone1' => $request -> telefone1,
            'telefone2' => $request -> telefone2,
            'site' => $request -> site,
            'email' => $request -> email,
            'logo1' => $logo1_path,
            'logo2' => $logo2_path,
            'data' => date("Y-m-d"),
            'usuario' => Auth::user()->indice_adm,
        ]);
        $subject = 'Edição Configuração do SIGDEC - '.$registro -> Orgao['sigla'];

        if(!$config){
            return back() -> with('error', 'Erro ao editar Configuração!')
                          -> withInput($request -> input());
        }else{
            $mensagem  = 'O Usuário: '.Auth::user()->nome.' editou a configuração do SIGDEC.<br>';
            $mensagem .= 'Fonte: '.$request -> fonte.'<br>';
            $mensagem .= 'Fundo: '.$request -> fundo.'<br>';
            $mensagem .= 'Título 1: '.$request -> titulo1.'<br>';
            $mensagem .= 'Título 2: '.$request -> titulo2.'<br>';
            $mensagem .= 'Logo 1: '.$logo1_path.'<br>';
            $mensagem .= 'Logo 2: '.$logo2_path.'<br>';
            $mensagem .= 'Orgão: '.$registro -> Orgao['sigla'].'<br>';
            $mensagem .= 'Titular: '.$request -> titular.'<br>';
            $mensagem .= 'Titular/Função: '.$request -> funcao_titular.'<br>';
            $mensagem .= 'Titular/Matrícula: '.$request -> mat_titular.'<br>';
            $mensagem .= 'Endereço: '.$request -> endereco.'<br>';
            $mensagem .= 'Telefone 1: '.$request -> telefone1.'<br>';
            $mensagem .= 'Telefone 2: '.$request -> telefone2.'<br>';
            $mensagem .= 'site: '.$request -> site.'<br>';
            $mensagem .= 'E-mail: '.$request -> email.'<br>';
            $mensagem .= 'Data da Edição: '.date("d/m/Y").'<br>';

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user()->email,
            ];
            try {
                Mail::to(Auth::user()->email)
                ->send( new SendMail($data));
                return back()->with('success', 'Configuração editada com sucesso!');

            } catch (\Throwable $th) {
                return back()->with('warning', 'Não foi possível enviar confirmação por E-mail!');
            }    
        }
    }
}
