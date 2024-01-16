<?php

namespace App\Http\Controllers\auth\diagnose;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\diagnose\grupos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class gruposController extends Controller
{
    public function index()
    {
        $rota = 'd.grupo.create';

        $grupo = new grupos();
        $grupo -> id = 0;
        $grupo -> grupo = '';
        $grupo -> ordem = '';
        $grupo -> ativo = '';

        $grupos = grupos::orderBy('ordem')->cursor();
        
        return view('auth.diagnose.grupos')->with('grupo', $grupo)
                                           ->with('grupos', $grupos)
                                           ->with('rota', $rota);
    }
//********************************************************************************** */
    public function edit($id)
    {
        $rota = 'd.grupo.edit';

        $grupo = grupos::findOrFail($id);

        $grupos = grupos::orderBy('ordem')->cursor();
        
        return view('auth.diagnose.grupos')->with('grupo', $grupo)
                                           ->with('grupos', $grupos)
                                           ->with('rota', $rota);
    }
//********************************************************************************** */
    public function create(Request $request)
    {
        $request -> validate([
            'grupo' => 'required',
            'ordem' => 'required',
            'ativo' => 'required',
        ]);

        $teste = grupos::where('grupo', $request -> grupo);

        if($teste -> count() > 0){
            return back() -> with('warning', 'Grupo já cadastrado!')
                          -> withInput($request -> input());
        }

        $grupo = grupos::create([
            'grupo' => \mb_strtoupper($request -> grupo),
            'ordem' => $request -> ordem,
            'ativo' => $request -> ativo,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($grupo){
            $subject = "Cadastro de Grupo de Questões";
            $mensagem = "O usuário ".Auth::user() -> nome." cadastrou um grupo de questões.<br>";
            $mensagem .= "Grupo: ".$grupo ->grupo."<br>";
            $mensagem .= "Ordem: ".$grupo ->ordem."<br>";
            $mensagem .= "Ativo: ".($grupo ->ativo == '1' ? 'Sim' : ($grupo -> ativo == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Data do cadastro: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return back() -> with(['success' => 'Grupo cadastrado com sucesso!']);

            } 
            catch (\Throwable $th) {
                return back() -> with(['warning' => 'Grupo cadastrado, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao cadastrar Grupo!'])
                          -> withInput($request ->input());
        }
    }
//******************************************************************************************

    public function update(Request $request, $id)
    {
        $grupo = grupos::findOrFail($id);

        $request -> validate([
            'grupo' => 'required',
            'ordem' => 'required',
            'ativo' => 'required',
        ]);

        if($request-> grupo != $grupo -> grupo){
            $teste = grupos::where('grupo', $request -> grupo);

            if($teste -> count() > 0){
                return back() -> with('warning', 'Grupo já cadastrado!')
                            -> withInput($request -> input());
            }
        }

        if($request-> ordem != $grupo -> ordem){
            $ordem_antiga = $grupo -> ordem;
            $ordem_nova = $request -> ordem;

            if($ordem_nova < $ordem_antiga){
                $ordenar = grupos::where('ordem', '>=', $ordem_nova)
                                    ->where('ordem', '<', $ordem_antiga)
                                    ->get();
                DB::beginTransaction();
                foreach($ordenar as $item){
                    $item -> update([
                        'ordem' => $item -> ordem + 1,
                    ]);
                }
                $grupo -> update([
                    'ordem' => $ordem_nova,
                ]);
            }
            if($ordem_nova > $ordem_antiga){
                $ordenar = grupos::where('ordem', '<=', $ordem_nova)
                                    ->where('ordem', '>', $ordem_antiga)
                                    ->get();
                DB::beginTransaction();
                foreach($ordenar as $item){
                    $item -> update([
                        'ordem' => $item -> ordem - 1,
                    ]);
                }
                $grupo -> update([
                    'ordem' => $ordem_nova,
                ]);
            }
        }


        $update = $grupo -> update([
            'grupo' => \mb_strtoupper($request -> grupo),
            'ordem' => $request -> ordem,
            'ativo' => $request -> ativo,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($update){
            DB::commit();
            $subject = "Edição de Grupo de Questões";
            $mensagem = "O usuário ".Auth::user() -> nome." editou um grupo de questões.<br>";
            $mensagem .= "Grupo: ".$grupo ->grupo."<br>";
            $mensagem .= "Ordem: ".$grupo ->ordem."<br>";
            $mensagem .= "Ativo: ".($grupo ->ativo == '1' ? 'Sim' : ($grupo -> ativo == '0' ? 'Não' : 'Não especificado'))."<br>";
            $mensagem .= "Data da edição: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return \redirect()->route('d.grupo.index') -> with(['success' => 'Grupo editado com sucesso!']);

            } 
            catch (\Throwable $th) {
                return redirect()->route('d.grupo.index') -> with(['warning' => 'Edição concluída, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            DB::rollBack();
            return back() -> with(['error' => 'Erro ao editar grupo!'])
                          -> withInput($request ->input());
        }
    }

}
