<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class op_viatura extends Model
{
    use HasFactory;
    protected $table = "op_viatura";
    protected $primaryKey = 'id_opvtr';
    public $timestamps = false;

    protected $fillable = [
        'id_viatura',
        'id_atv',
        'kilometragem',
        'ativo',
    ];

    public function Atividade()
    {
        return $this -> belongsTo(atividades::class, 'id_atv', 'id_atv');
    }

    public function Viatura()
    {
        return $this -> belongsTo(viaturas::class, 'id_viatura', 'id_viatura');
    }
}
