<?php

namespace App\Models\diagnose;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class opcoes extends Model
{
    use HasFactory;
    protected $table = "diag_opcoes";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'id_questao',
        'opcao',
        'ordem',
        'comentar',
        'score',
        'ativo',
        'data_cadastro',
        'usuario',
    ];

    public function Questao()
    {
        return $this -> belongsTo(questao::class, 'id_questao', 'id');
    }
}
