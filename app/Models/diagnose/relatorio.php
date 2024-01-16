<?php

namespace App\Models\diagnose;

use App\Models\redec;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relatorio extends Model
{
    use HasFactory;
    protected $table = "diag_relatorio";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_edicao',
        'id_grupo',
        'id_questao',
        'respostas',
        'comentario',
        'municipio',
        'data_cadastro',
        'usuario',
    ];

    public function Edicao()
    {
        return $this -> belongsTo(edicao::class, 'id_edicao', 'id');
    }

    public function Grupo()
    {
        return $this -> belongsTo(grupos::class, 'id_grupo', 'id');
    }

    public function Questao()
    {
        return $this -> belongsTo(questao::class, 'id_questao', 'id');
    }

    public function Redec()
    {
        return $this -> belongsTo(redec::class, 'municipio', 'id_redec');
    }
}
