<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class viaturas extends Model
{
    use HasFactory;
    protected $table = "viaturas";
    protected $primaryKey = 'id_viatura';
    public $timestamps = false;

    protected $fillable = [
        'prefixo',
        'tipo',
        'placa',
        'ativo',
        'orgao_carga',
        'orgao_utilizado',
        'baixada',
    ];

    public function AtividadesV()
    {
        return $this -> hasMany(op_viatura::class, 'id_viatura', 'id_viatura');
    }
}
