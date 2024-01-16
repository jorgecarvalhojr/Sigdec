<?php

namespace App\Models;

use App\Models\diagnose\relatorio;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class redec extends Model
{
    use HasFactory;

    protected $table = "redec";
    protected $primaryKey = 'id_redec';

    public function Orgao()
    {
        return $this -> belongsTo(orgao::class, 'id_orgao', 'id_orgao');
    }

    public function Relatorios()
    {
        return $this -> hasMany(relatorio::class, 'municipio', 'id_redec');
    }
}
