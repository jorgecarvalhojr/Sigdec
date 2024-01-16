<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\orgao;
use App\Models\permissoes;
use App\Models\Postos;
use App\Models\redec;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $rota = 'user.create';
        $user = new User();
        $user -> indice_adm = 0;
        $user -> nome = "";
        $user -> posto;
        $user -> email = "";
        $user -> permissao = "";
        $user -> acesso = "";
        $user -> orgao = "";
        $user -> nome_guerra = "";
        $user -> ativo = 'Sim';
        $user -> uf = Auth::user() -> uf;
        $user -> municipio = "";

        $municipios = redec::select('municipio')->orderBy('municipio')->get();
        $orgaos = orgao::orderBy('sigla')->get();
        $postos = Postos::orderBy('id_posto')->get();

        return view('auth.user')-> with('user', $user)
                                -> with('rota', $rota)
                                -> with('orgaos', $orgaos)
                                -> with('postos', $postos)
                                -> with('municipios', $municipios);
    }
//****************************************************************************************/
    public function create(Request $request)
    {
        $request -> validate([

            'nome' => 'required',
            'uf' => 'required',
            'municipio' => 'required',
            'matricula' => 'required',
            'email' => 'required|email',
            'posto' => 'required',
            'permissao' => 'required',
            'orgao' => 'required',
            'acesso' => 'required',
            'nome_guerra' => 'required',
            'ativo' => 'required',
            'senha' => 'min:8',
        ]);

        $senha = Hash::make($request -> senha);

        $teste = User::where('email', $request -> email);
        if($teste -> count()){
            return back() -> with(['warning' => 'E-mail já cadastrado!'])
                          -> withInput($request->input());
        }

        $usuario = User::create([
            'nome' => $request -> nome,
            'uf' => $request -> uf,
            'municipio' => $request -> municipio,
            'matricula' => $request -> matricula,
            'email' => $request -> email,
            'senha' => $senha,
            'permissao' => $request -> permissao,
            'acesso' => $request -> acesso,
            'orgao' => $request -> orgao,
            'nome_guerra' => $request -> nome_guerra,
            'ativo' => $request -> ativo,
            'posto' => $request -> posto,
            'data_cadastro' => date("Y-m-d"),
        ]);
        if($usuario){

            $mensagem  = 'Nome: '.$request -> nome.'<br>';
            $mensagem  = 'Nome de Guerra: '.$request -> nome_guerra.'<br>';
            $mensagem .= 'Posto: '.$request -> posto.'<br>';
            $mensagem .= 'Órgão: '.$usuario -> Orgao['sigla'].'<br>';
            $mensagem .= 'Senha: '.$request -> senha.'<br>';
            $mensagem .= 'E-mail: '.$request -> email.'<br>';
            $mensagem .= 'Ativo: '.$request -> ativo.'<br>';
            if($request -> permissao == '1'){
                $mensagem .= 'Permissão: Usuário <br>';
            }
            if($request -> permissao == '2'){
                $mensagem .= 'Permissão: Administrador <br>';
            }
            if($request -> acesso == '1'){
                $mensagem .= 'Acesso: Básico <br>';
            }
            if($request -> acesso == '2'){
                $mensagem .= 'Acesso: Gestor <br>';
            }
            $mensagem .= 'Data do Cadastro: '.date("d/m/Y").'<br>';

            $data = [
                'subject' => 'Cadastro de Usuário - '.$usuario -> Orgao['sigla'],
                'mensagem' => $mensagem,
                'email' => $request -> email,
            ];
            try {
                Mail::to($request -> email)
                ->bcc(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Usuário cadastrado com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Houve um problema no envio da mensagem de cadastro!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao criar usuário!'])
                          -> withInput($request ->input());
        }
    }

    /*************************************************************************************************/
    public function show()
    {
        $users = User::orderBy('nome') -> get();
        return view('auth.users')-> with('users', $users);
    }

    /*************************************************************************************************/
    public function edit($id)
    {
        $rota = 'user.update';
        $user = User::findOrFail($id);

        $municipios = redec::select('municipio')->orderBy('municipio')->get();
        $orgaos = orgao::orderBy('sigla')->get();
        $postos = Postos::orderBy('id_posto')->get();

        return view('auth.user')-> with('user', $user)
                                -> with('rota', $rota)
                                -> with('orgaos', $orgaos)
                                -> with('postos', $postos)
                                -> with('municipios', $municipios);
    }
    /*************************************************************************************************/
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $rota = "user.update";
        $request -> validate([

            'nome' => 'required',
            'uf' => 'required',
            'municipio' => 'required',
            'matricula' => 'required',
            'email' => 'required|email',
            'posto' => 'required',
            'permissao' => 'required',
            'orgao' => 'required',
            'acesso' => 'required',
            'nome_guerra' => 'required',
            'ativo' => 'required',
        ]);

        if ($request -> senha != "") {
            $request -> validate (['senha' => 'min:8']);
            $senha = Hash::make($request -> senha);
        }
        else{
            $senha = $user -> senha;
        }

        if($user -> email != $request -> email){
            $teste = User::where('email', $request -> email);
            if($teste -> count()){
                return back() -> with(['warning' => 'E-mail já cadastrado!'])
                              -> withInput($request->input());
            }
        }

        $usuario = $user -> update([
            'nome' => $request -> nome,
            'uf' => $request -> uf,
            'municipio' => $request -> municipio,
            'matricula' => $request -> matricula,
            'email' => $request -> email,
            'senha' => $senha,
            'permissao' => $request -> permissao,
            'acesso' => $request -> acesso,
            'orgao' => $request -> orgao,
            'nome_guerra' => $request -> nome_guerra,
            'ativo' => $request -> ativo,
            'posto' => $request -> posto,
            'data_cadastro' => date("Y-m-d"),
        ]);
        if($usuario){

            $mensagem  = 'Nome: '.$request -> nome.'<br>';
            $mensagem .= 'Posto: '.$request -> posto.'<br>';
            $mensagem .= 'Órgão: '.$user -> Orgao['sigla'].'<br>';
            $mensagem .= 'Senha: '.$request -> senha.'<br>';
            $mensagem .= 'E-mail: '.$request -> email.'<br>';
            $mensagem .= 'Ativo: '.$request -> ativo.'<br>';
            if($request -> permissao == '1'){
                $mensagem .= 'Permissão: Usuário <br>';
            }
            if($request -> permissao == '2'){
                $mensagem .= 'Permissão: Administrador <br>';
            }
            if($request -> acesso == '1'){
                $mensagem .= 'Acesso: Básico <br>';
            }
            if($request -> acesso == '2'){
                $mensagem .= 'Acesso: Gestor <br>';
            }
            if ($request -> senha != "") {
                $mensagem .= 'Senha: '.$request -> senha.'<br>';
            }
            $mensagem .= 'Data da Edição: '.date("d/m/Y").'<br>';

            $data = [
                'subject' => 'Edição de Usuário - '.$user -> Orgao['sigla'],
                'mensagem' => $mensagem,
                'email' => $request -> email,
            ];
            try {
                Mail::to($request -> email)
                ->bcc(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Usuário editado com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Houve um problema no envio da mensagem de edição!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao editar usuário!']);
        }
    }

}
