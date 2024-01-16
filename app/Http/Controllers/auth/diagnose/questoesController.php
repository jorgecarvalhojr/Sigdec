<?php

namespace App\Http\Controllers\auth\diagnose;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\diagnose\grupos;
use App\Models\diagnose\opcoes;
use App\Models\diagnose\questao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class questoesController extends Controller
{
    public function index()
    {
        $rota = 'd.questao.create';

        $questao = new questao();
        $questao -> id = 0;
        $questao -> questao = '';
        $questao -> id_grupo = '';
        $questao -> tipo = '';
        $questao -> ordem = '';
        $questao -> ativo = '';

        $questoes = questao::orderBy('ordem')->cursor();
        $grupos = grupos::where('ativo', 1) -> orderBy('ordem')->cursor();
        $opcoes = opcoes::where('ativo', 1) -> orderBy('ordem')->cursor();
        
        return view('auth.diagnose.questoes')->with('questao', $questao)
                                           ->with('questoes', $questoes)
                                           ->with('opcoes', $opcoes)
                                           ->with('grupos', $grupos)
                                           ->with('rota', $rota);
    }
//********************************************************************************** */
    public function edit($id)
    {
        $rota = 'd.questao.edit';

        $questao = questao::findOrFail($id);

        $questoes = questao::orderBy('ordem')->cursor();

        $grupos = grupos::where('ativo', 1)->orderBy('ordem')->cursor();

        $opcoes = opcoes::where('ativo', 1) -> orderBy('ordem')->cursor();
        
        return view('auth.diagnose.questoes')->with('questao', $questao)
                                           ->with('questoes', $questoes)
                                           ->with('opcoes', $opcoes)
                                           ->with('grupos', $grupos)
                                           ->with('rota', $rota);
    }
//********************************************************************************** */
    public function create(Request $request)
    {
        $request -> validate([
            'questao' => 'required',
            'id_grupo' => 'required',
            'tipo' => 'required',
            'ordem' => 'required',
            'ativo' => 'required',
        ]);

        $teste = questao::where('questao', $request -> questao);

        if($teste -> count() > 0){
            return back() -> with('warning', 'questao já cadastrada!')
                          -> withInput($request -> input());
        }

        $teste2 = questao::where('ordem', $request -> ordem)
                         ->where('ativo', '1');

        if($teste2 -> count() > 0){
            return back() -> with('warning', 'Já existe uma questão nesta ordem!')
                          -> withInput($request -> input());
        }

        $questao = questao::create([
            'questao' => $request -> questao,
            'ordem' => $request -> ordem,
            'tipo' => $request -> tipo,
            'id_grupo' => $request -> id_grupo,
            'ativo' => $request -> ativo,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($questao){
            $subject = "Cadastro de Questão";
            $mensagem = "O usuário ".Auth::user() -> nome." cadastrou uma questão.<br>";
            $mensagem .= "questao: ".$questao ->questao."<br>";
            $mensagem .= "Grupo: ".$questao ->Grupo['grupo']."<br>";
            $mensagem .= "Ordem: ".$questao ->ordem."<br>";
            $mensagem .= "Ativo: ".($questao ->ativo == '1' ? 'Sim' : ($questao -> ativo == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Tipo de Resposta: ".($questao ->ativo == '1' ? 'Múltipla' : ($questao -> ativo == '0' ? 'Única' : 'Não especificado'))."<br>";
            $mensagem .= "Data do cadastro: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Questão cadastrada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Questão cadastrada, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao cadastrar questão!'])
                          -> withInput($request ->input());
        }
    }
//******************************************************************************************

    public function update(Request $request, $id)
    {
        $questao = questao::findOrFail($id);

        $request -> validate([
            'questao' => 'required',
            'id_grupo' => 'required',
            'tipo' => 'required',
            'ordem' => 'required',
            'ativo' => 'required',
        ]);

        if($request-> questao != $questao -> questao){
            $teste = questao::where('questao', $request -> questao);

            if($teste -> count() > 0){
                return back() -> with('warning', 'Questão já cadastrada!')
                            -> withInput($request -> input());
            }
        }

        if($request-> ordem != $questao -> ordem){
            $ordem_antiga = $questao -> ordem;
            $ordem_nova = $request -> ordem;

            if($ordem_nova < $ordem_antiga){
                $ordenar = questao::where('ordem', '>=', $ordem_nova)
                                    ->where('ordem', '<', $ordem_antiga)->get();
                DB::beginTransaction();
                foreach($ordenar as $item){
                    $item -> update([
                        'ordem' => $item -> ordem + 1,
                    ]);
                }
                $questao -> update([
                    'ordem' => $ordem_nova,
                ]);
            }
            if($ordem_nova > $ordem_antiga){
                $ordenar = questao::where('ordem', '<=', $ordem_nova)->get()
                                    ->where('ordem', '>', $ordem_antiga);
                DB::beginTransaction();
                foreach($ordenar as $item){
                    $item -> update([
                        'ordem' => $item -> ordem - 1,
                    ]);
                }
                $questao -> update([
                    'ordem' => $ordem_nova,
                ]);
            }
        }

        $update = $questao -> update([
            'questao' => $request -> questao,
            'id_grupo' => $request -> id_grupo,
            'tipo' => $request -> tipo,
            'ativo' => $request -> ativo,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($update){
            DB::commit();
            $subject = "Edição de Questão";
            $mensagem = "O usuário ".Auth::user() -> nome." editou uma questão.<br>";
            $mensagem .= "questao: ".$questao ->questao."<br>";
            $mensagem .= "Grupo: ".$questao ->Grupo['grupo']."<br>";
            $mensagem .= "Ordem: ".$questao ->ordem."<br>";
            $mensagem .= "Ativo: ".($questao ->ativo == '1' ? 'Sim' : ($questao -> ativo == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Tipo de Resposta: ".($questao ->ativo == '1' ? 'Múltipla' : ($questao -> ativo == '0' ? 'Única' : 'Não especificado'))."<br>";
            $mensagem .= "Data da edição: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return \redirect()->route('d.questao.index') -> with(['success' => 'Questão editada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return redirect()->route('d.questao.index') -> with(['warning' => 'Edição concluída, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            DB::rollBack();
            return back() -> with(['error' => 'Erro ao editar questão!'])
                          -> withInput($request ->input());
        }
    }
}
