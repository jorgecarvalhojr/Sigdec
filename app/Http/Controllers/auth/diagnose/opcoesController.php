<?php

namespace App\Http\Controllers\auth\diagnose;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\diagnose\opcoes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class opcoesController extends Controller
{
    public function index($id)
    {

        $rota = 'd.opcao.create';

        $opcao = new opcoes();
        $opcao -> id = 0;
        $opcao -> id_questao = $id;
        $opcao -> opcao = '';
        $opcao -> comentar = '';
        $opcao -> score = '';
        $opcao -> ordem = '';
        $opcao -> ativo = '';

        $opcoes = opcoes::where('id_questao', $id)->orderBy('ordem')->cursor();
        
        return view('auth.diagnose.opcoes')->with('opcao', $opcao)
                                           ->with('opcoes', $opcoes)
                                           ->with('rota', $rota);
    }
//********************************************************************************** */
    public function edit($id)
    {
        $rota = 'd.opcao.edit';

        $opcao = opcoes::findOrFail($id);

        $opcoes = opcoes::where('id_questao', $opcao -> id_questao) -> orderBy('ordem')->cursor();
        
        return view('auth.diagnose.opcoes')->with('opcao', $opcao)
                                           ->with('opcoes', $opcoes)
                                           ->with('rota', $rota);
    }
//********************************************************************************** */
    public function create(Request $request, $id)
    {
        $request -> validate([
            'opcao' => 'required',
            'score' => 'required',
            'comentar' => 'required',
            'ordem' => 'required',
            'ativo' => 'required',
        ]);

        $teste = opcoes::where('opcao', $request -> opcao)
                        ->where('id_questao', $id);

        if($teste -> count() > 0){
            return back() -> with('warning', 'Opção já cadastrada!')
                          -> withInput($request -> input());
        }

        $teste2 = opcoes::where('ordem', $request -> ordem)
                         ->where('id_questao', $id)
                         ->where('ativo', '1');

        if($teste2 -> count() > 0){
            return back() -> with('warning', 'Já existe uma opção nesta ordem!')
                          -> withInput($request -> input());
        }

        $opcao = opcoes::create([
            'opcao' => \mb_strtoupper($request -> opcao),
            'ordem' => $request -> ordem,
            'score' => $request -> score,
            'comentar' => $request -> comentar,
            'id_questao' => $id,
            'ativo' => $request -> ativo,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($opcao){
            $subject = "Cadastro de Opção";
            $mensagem = "O usuário ".Auth::user() -> nome." cadastrou uma opção de questão.<br>";
            $mensagem .= "Questão: ".$opcao ->Questao['questao']."<br>";
            $mensagem .= "Opção: ".$opcao ->opcao."<br>";
            $mensagem .= "Score: ".$opcao ->score."<br>";
            $mensagem .= "Ordem: ".$opcao ->ordem."<br>";
            $mensagem .= "Ativo: ".($opcao ->ativo == '1' ? 'Sim' : ($opcao -> ativo == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Requer Comentário: ".($opcao ->comentar == '1' ? 'Sim' : ($opcao -> comentar == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Data do cadastro: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Opção cadastrada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Opção cadastrada, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao cadastrar opção!'])
                          -> withInput($request ->input());
        }
    }
//******************************************************************************************

    public function update(Request $request, $id)
    {
        $opcao = opcoes::findOrFail($id);

        $request -> validate([
            'opcao' => 'required',
            'score' => 'required',
            'comentar' => 'required',
            'ordem' => 'required',
            'ativo' => 'required',
        ]);

        if($request-> opcao != $opcao -> opcao){
            $teste = opcoes::where('opcao', $request -> opcao)
                            ->where('id_questao', $id);

            if($teste -> count() > 0){
                return back() -> with('warning', 'Opção já cadastrada!')
                            -> withInput($request -> input());
            }
        }

        if($request-> ordem != $opcao -> ordem){
            $ordem_antiga = $opcao -> ordem;
            $ordem_nova = $request -> ordem;

            if($ordem_nova < $ordem_antiga){
                $ordenar = opcoes::where('ordem', '>=', $ordem_nova)
                                    ->where('ordem', '<', $ordem_antiga)
                                    ->where('id_questao', $opcao ->id_questao)
                                    ->get();
                DB::beginTransaction();
                foreach($ordenar as $item){
                    $item -> update([
                        'ordem' => $item -> ordem + 1,
                    ]);
                }
                $opcao -> update([
                    'ordem' => $ordem_nova,
                ]);
            }
            if($ordem_nova > $ordem_antiga){
                $ordenar = opcoes::where('ordem', '<=', $ordem_nova)
                                    ->where('ordem', '>', $ordem_antiga)
                                    ->where('id_questao', $opcao ->id_questao)
                                    ->get();
                DB::beginTransaction();
                foreach($ordenar as $item){
                    $item -> update([
                        'ordem' => $item -> ordem - 1,
                    ]);
                }
                $opcao -> update([
                    'ordem' => $ordem_nova,
                ]);
            }
        }

        $update = $opcao -> update([
            'opcao' => \mb_strtoupper($request -> opcao),
            'score' => $request -> score,
            'comentar' => $request -> comentar,
            'ativo' => $request -> ativo,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($update){
            DB::commit();
            $subject = "Edição de Opção";
            $mensagem = "O usuário ".Auth::user() -> nome." editou uma questão.<br>";
            $mensagem .= "Questão: ".$opcao ->Questao['questao']."<br>";
            $mensagem .= "Opção: ".$opcao ->opcao."<br>";
            $mensagem .= "Score: ".$opcao ->score."<br>";
            $mensagem .= "Ordem: ".$opcao ->ordem."<br>";
            $mensagem .= "Ativo: ".($opcao ->ativo == '1' ? 'Sim' : ($opcao -> ativo == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Requer Comentário: ".($opcao ->comentar == '1' ? 'Sim' : ($opcao -> comentar == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Data da edição: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return \redirect()->route('d.opcao.index',['idq' => $opcao -> id_questao]) -> with(['success' => 'Questão editada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return redirect()->route('d.opcao.index',['idq' => $opcao -> id_questao]) -> with(['warning' => 'Edição concluída, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            DB::rollBack();
            return back() -> with(['error' => 'Erro ao editar opção!'])
                          -> withInput($request ->input());
        }
    }
}
