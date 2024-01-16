<?php

namespace App\Models\diagnose;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class questao extends Model
{
    use HasFactory;
    protected $table = "diag_questao";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'questao',
        'ordem',
        'id_grupo',
        'tipo',
        'ativo',
        'data_cadastro',
        'usuario',
    ];

    public function Grupo()
    {
        return $this -> belongsTo(grupos::class, 'id_grupo', 'id');
    }

    public function Opcoes()
    {
        return $this -> hasMany(opcoes::class, 'id_questao', 'id');
    }

    public function Relatorios()
    {
        return $this -> hasMany(relatorio::class, 'id_questao', 'id');
    }
}
