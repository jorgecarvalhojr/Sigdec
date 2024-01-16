<?php

namespace App\Models\diagnose;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class edicao extends Model
{
    use HasFactory;
    protected $table = "diag_edicao";
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'ano',
        'data_inicio',
        'data_fim',
        'ativo',
        'data_cadastro',
        'usuario',
    ];

    public function Relatorios()
    {
        return $this -> hasMany(relatorio::class, 'id_edicao', 'id');
    }
}
