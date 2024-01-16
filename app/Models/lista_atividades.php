<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class lista_atividades extends Model
{
    use HasFactory;
    protected $table = "lista_atividades";
    protected $primaryKey = 'id_atividade';

    public function Atividades()
    {
        return $this -> hasMany(atividades::class, 'tipo_atividade', 'id_atividade');
    }

}
