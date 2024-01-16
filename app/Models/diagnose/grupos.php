<?php

namespace App\Models\diagnose;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class grupos extends Model
{
    use HasFactory;
    protected $table = "diag_grupo";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'grupo',
        'ordem',
        'ativo',
        'data_cadastro',
        'usuario',
    ];

    public function Questoes()
    {
        return $this -> hasMany(questao::class, 'id_grupo', 'id');
    }

    public function Relatorios()
    {
        return $this -> hasMany(relatorio::class, 'id_grupo', 'id');
    }

}
