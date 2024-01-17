<?php

namespace App\Http\Controllers\auth\diagnose;

use App\Http\Controllers\Controller;
use App\Mail\SendMail;
use App\Models\diagnose\edicao;
use App\Models\diagnose\grupos;
use App\Models\diagnose\opcoes;
use App\Models\diagnose\questao;
use App\Models\diagnose\relatorio;
use App\Models\redec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class diagnoseController extends Controller
{
    public function edicaoShow()
    {
        $edicoes = edicao::orderBy('ano', 'desc')->get();

        return \view('auth.diagnose.edicoes_lista')->with('edicoes', $edicoes);
    }
//********************************************************************************************** */
    public function municipios($id)
    {
        $edicao = edicao::findOrFail($id);
        if(Auth::user() -> acesso == '2'){
            $municipios = redec::orderBy('municipio')->cursor();
        }else{
            $municipios = redec::where('id_orgao', Auth::user() -> orgao)->cursor();
            if($municipios -> count() == 0){
                return \back()->with('warning', 'Você não está habilitado para acessar esta área!');
            }
        }

        $questoes = questao::where('ativo', '1')->cursor();
        $respostas = relatorio::where('id_edicao', $edicao -> id)->cursor();

        return view('auth.diagnose.municipios')->with('edicao', $edicao)
                                                ->with('municipios', $municipios)
                                                ->with('questoes', $questoes)
                                                ->with('respostas', $respostas);
    }

//********************************************************************************************* */
public function municipio($ide, $idm)
    {
        $edicao = edicao::findOrFail($ide);

        $municipio = redec::findOrFail($idm);

        $grupos = grupos::where('ativo', '1')->orderBy('ordem')->cursor();

        $questoes = questao::where('ativo', '1')->cursor();
        $respostas = relatorio::where('id_edicao', $edicao -> id)->cursor();

        return view('auth.diagnose.municipio')->with('edicao', $edicao)
                                                ->with('municipio', $municipio)
                                                ->with('grupos', $grupos)
                                                ->with('questoes', $questoes)
                                                ->with('respostas', $respostas);
    }
//********************************************************************************************* */
public function questoes($ide, $idm, $idg)
    {
        $edicao = edicao::findOrFail($ide);

        $grupo = grupos::findOrFail($idg);

        $municipio = redec::findOrFail($idm);

        $questoes = questao::where('ativo', '1')->where('id_grupo',$idg)->orderBy('ordem')->cursor();

        $respostas = relatorio::where('id_edicao', $edicao -> id)->cursor();

        return view('auth.diagnose.questoes_lista')->with('edicao', $edicao)
                                                ->with('municipio', $municipio)
                                                ->with('questoes', $questoes)
                                                ->with('grupo', $grupo)
                                                ->with('respostas', $respostas);
    }
//********************************************************************************************* */
    public function responder($ide, $idm, $idq)
    {
        $edicao = edicao::findOrFail($ide);

        $questao = questao::findOrFail($idq);

        $municipio = redec::findOrFail($idm);

        $opcoes = opcoes::where('id_questao', $idq)->where('ativo', '1')->cursor();

        $resposta = relatorio::where('id_edicao', $ide)
                             ->where('municipio', $idm)
                             ->where('id_questao', $idq)
                             ->get();


        return view('auth.diagnose.questao_resposta')->with('edicao', $edicao)
                                                ->with('municipio', $municipio)
                                                ->with('questao', $questao)
                                                ->with('opcoes', $opcoes)
                                                ->with('resposta', $resposta);
    }

//********************************************************************************************* */
    public function create(Request $request)
    {
        $request -> validate([
            'id_questao' => 'required',
            'id_grupo' => 'required',
            'municipio' => 'required',
            'id_edicao' => 'required',
            'opcao' => 'array|required',
        ]);

        $opcoes = implode(',', $request -> opcao);
        $comentarios = null;
        foreach($request -> opcao as $item)
        {
            $opcao = opcoes::findOrFail($item);
            if($opcao -> comentar == 1)
            {
                $request -> validate(
                    [
                        'comentar'.$opcao -> ordem => 'required',
                    ],
                    [
                        'comentar'.$opcao -> ordem.'.required' => 'O comentário é obrigatório!',
                    ]
                );
                $comentario = "comentar".$opcao -> ordem;
                $comentarios .= $opcao -> ordem.",".$request -> $comentario.";";
            }
        }
        $comentarios = \substr($comentarios, 0, -1);

        $teste = relatorio::where('id_questao', $request -> id_questao)
                          ->where('id_grupo', $request -> id_grupo)
                          ->where('municipio', $request -> municipio)
                          ->where('id_edicao', $request -> id_edicao)->get();
        if($teste -> count() > 0)
        {
            return \redirect() -> route('diagnose.questoes', ['ide' => $request -> id_edicao, 'idm' => $request -> municipio, 'idg' => $request -> id_grupo])-> with('warning', 'Essa questão já foi respondida');
        }

        $resposta = relatorio::create([
            'id_edicao' => $request -> id_edicao,
            'id_grupo' => $request -> id_grupo,
            'id_questao' => $request -> id_questao,
            'municipio' => $request -> municipio,
            'respostas' => $opcoes,
            'comentario' => $comentarios,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($resposta){
            if(!is_dir("storage/relatorios/".$resposta -> Edicao['ano'])) {mkdir("storage/relatorios/".$resposta -> Edicao['ano']."/", 0755);}
            $relatorio = relatorio::where('id_edicao', $resposta -> id_edicao)->cursor();
            foreach($relatorio as $item){
                $respostas = explode(',', $item -> respostas);
                foreach($respostas as $teste){
                    $opcoes = opcoes::findOrFail($teste);
                    if($opcoes -> comentar == 1){
                        $coment = explode(';', $item -> comentario);
                        foreach($coment as $test){
                            $cod = explode(',', $test);
                            if($cod[0] == $opcoes -> ordem){
                                $comentario = $cod[1];
                                break;
                            }
                        }
                        $dados[] = Array("Ano" => $item -> Edicao['ano'], "Registro" => date('d/m/Y H:i:s', strtotime($item -> data_cadastro)), "ordem" => $item -> Grupo['ordem'].".".$item -> Questao['ordem'], "Grupo" => $item -> Grupo['grupo'], "Questão" => $item -> Questao['questao'], "Resposta" => $opcoes-> opcao, "Comentário" => $comentario, "Município" => $item -> Redec['municipio'], "REDEC" => $item ->Redec['sigla']);
                    }else{
                        $dados[] = Array("Ano" => $item -> Edicao['ano'], "Registro" => date('d/m/Y H:i:s', strtotime($item -> data_cadastro)), "ordem" => $item -> Grupo['ordem'].".".$item -> Questao['ordem'], "Grupo" => $item -> Grupo['grupo'], "Questão" => $item -> Questao['questao'], "Resposta" => $opcoes-> opcao, "Comentário" => "", "Município" => $item -> Redec['municipio'], "REDEC" => $item ->Redec['sigla']);
                    }
                }
            }

			$output = fopen(public_path('storage/relatorios/'.$resposta -> Edicao['ano'].'/RD'.$resposta -> Edicao['ano'].'.csv'), 'w');
			$header = \mb_convert_encoding(["Ano", "Registro", "ordem", "Grupo", "Questão", "Resposta", "Comentário", "Município", "REDEC"], "ISO-8859-1");
			fputcsv($output , $header);
			foreach($dados as $linha){
				fputcsv($output, \mb_convert_encoding((array) $linha, "ISO-8859-1"));
			}
			fclose($output);

            $subject = "Cadastro de Resposta - Relatório Diagnose - ".$resposta -> Redec['municipio'];
            $mensagem = "O usuário ".Auth::user() -> nome." cadastrou uma resposta no R.D.<br>";
            $mensagem .= "Edição: ".$resposta ->Edicao['ano']."<br>";
            $mensagem .= "Grupo de Questões: ".$resposta ->Grupo['grupo']."<br>";
            $mensagem .= "Município: ".$resposta ->Redec['municipio']."<br>";
            $mensagem .= "Questão: ".$resposta ->Questao['questao']."<br>";
            $mensagem .= "Data do cadastro: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return \redirect() -> route('diagnose.questoes', ['ide' => $request -> id_edicao, 'idm' => $request -> municipio, 'idg' => $request -> id_grupo]) -> with(['success' => 'Resposta cadastrada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return \redirect() -> route('diagnose.questoes', ['ide' => $request -> id_edicao, 'idm' => $request -> municipio, 'idg' => $request -> id_grupo]) -> with(['warning' => 'Resposta cadastrada, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao cadastrar resposta!'])
                          -> withInput($request ->input());
        }

    }
//*************************************************************************************************** */
    public function edit(Request $request, $id)
    {
        $resposta = relatorio::findOrFail($id);
        $request -> validate([
            'id_questao' => 'required',
            'id_grupo' => 'required',
            'municipio' => 'required',
            'id_edicao' => 'required',
            'opcao' => 'array|required',
        ]);

        $opcoes = implode(',', $request -> opcao);
        $comentarios = null;
        foreach($request -> opcao as $item)
        {
            $opcao = opcoes::findOrFail($item);
            if($opcao -> comentar == 1)
            {
                $request -> validate(
                    [
                        'comentar'.$opcao -> ordem => 'required',
                    ],
                    [
                        'comentar'.$opcao -> ordem.'.required' => 'O comentário é obrigatório!',
                    ]
                );
                $comentario = "comentar".$opcao -> ordem;
                $comentarios .= $opcao -> ordem.",".$request -> $comentario.";";
            }
        }
        $comentarios = \substr($comentarios, 0, -1);

        $update = $resposta -> update([
            'respostas' => $opcoes,
            'comentario' => $comentarios,
            'data_cadastro' => date('Y-m-d H:i:s'),
            'usuario' => Auth::user() -> indice_adm,
        ]);

        if($update){

            if(!is_dir("storage/relatorios/".$resposta -> Edicao['ano'])) {mkdir("storage/relatorios/".$resposta -> Edicao['ano']."/", 0755);}
            $relatorio = relatorio::where('id_edicao', $resposta -> id_edicao)->cursor();
            foreach($relatorio as $item){
                $respostas = explode(',', $item -> respostas);
                foreach($respostas as $teste){
                    $opcoes = opcoes::findOrFail($teste);
                    if($opcoes -> comentar == 1){
                        $coment = explode(';', $item -> comentario);
                        foreach($coment as $test){
                            $cod = explode(',', $test);
                            if($cod[0] == $opcoes -> ordem){
                                $comentario = $cod[1];
                                break;
                            }
                        }
                        $dados[] = Array("Ano" => $item -> Edicao['ano'], "Registro" => date('d/m/Y H:i:s', strtotime($item -> data_cadastro)), "ordem" => $item -> Grupo['ordem'].".".$item -> Questao['ordem'], "Grupo" => $item -> Grupo['grupo'], "Questão" => $item -> Questao['questao'], "Resposta" => $opcoes-> opcao, "Comentário" => $comentario, "Município" => $item -> Redec['municipio'], "REDEC" => $item ->Redec['sigla']);
                    }else{
                        $dados[] = Array("Ano" => $item -> Edicao['ano'], "Registro" => date('d/m/Y H:i:s', strtotime($item -> data_cadastro)), "ordem" => $item -> Grupo['ordem'].".".$item -> Questao['ordem'], "Grupo" => $item -> Grupo['grupo'], "Questão" => $item -> Questao['questao'], "Resposta" => $opcoes-> opcao, "Comentário" => "", "Município" => $item -> Redec['municipio'], "REDEC" => $item ->Redec['sigla']);
                    }
                }
            }

			$output = fopen(public_path('storage/relatorios/'.$resposta -> Edicao['ano'].'/RD'.$resposta -> Edicao['ano'].'.csv'), 'w');
			$header = \mb_convert_encoding(["Ano", "Registro", "ordem", "Grupo", "Questão", "Resposta", "Comentário", "Município", "REDEC"], "ISO-8859-1");
			fputcsv($output , $header);
			foreach($dados as $linha){
				fputcsv($output, \mb_convert_encoding((array) $linha, "ISO-8859-1"));
			}
			fclose($output);
            
            $subject = "Edição de Resposta - Relatório Diagnose - ".$resposta -> Redec['municipio'];
            $mensagem = "O usuário ".Auth::user() -> nome." editou uma resposta no R.D.<br>";
            $mensagem .= "Edição: ".$resposta ->Edicao['ano']."<br>";
            $mensagem .= "Grupo de Questões: ".$resposta ->Grupo['grupo']."<br>";
            $mensagem .= "Município: ".$resposta ->Redec['municipio']."<br>";
            $mensagem .= "Questão: ".$resposta ->Questao['questao']."<br>";
            $mensagem .= "Data da edição: ".date("d/m/Y")."<br>";

            $data = [
                'subject' => $subject,
                'mensagem' => $mensagem,
                'email' => Auth::user() -> email,
            ];
            try {
                Mail::to(Auth::user() -> email)
                ->send( new SendMail($data));
                return \redirect() -> route('diagnose.questoes', ['ide' => $request -> id_edicao, 'idm' => $request -> municipio, 'idg' => $request -> id_grupo]) -> with(['success' => 'Resposta editada com sucesso!']);

            } 
            catch (\Throwable $th) {
                return \redirect() -> route('diagnose.questoes', ['ide' => $request -> id_edicao, 'idm' => $request -> municipio, 'idg' => $request -> id_grupo]) -> with(['warning' => 'Resposta editada, mas houve um problema no envio da mensagem de retorno!']);
            }    

        }else{
            return back() -> with(['error' => 'Erro ao editar resposta!'])
                        -> withInput($request ->input());
        }

    }

}
