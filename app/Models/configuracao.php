<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class configuracao extends Model
{
    use HasFactory;
    protected $table = "configuracao";
    protected $primaryKey = 'id_config';
    public $timestamps = false;

    protected $fillable = [
        'orgao',
        'fundo',
        'fonte',
        'logo1',
        'logo2',
        'titulo1',
        'titulo2',
        'titular',
        'funcao_titular',
        'mat_titular',
        'endereco',
        'telefone1',
        'telefone2',
        'email',
        'data',
        'usuario',
        'site',
    ];

    public function Users()
    {
        return $this -> hasMany(User::class, 'orgao', 'orgao');
    }

    public function Orgao()
    {
        return $this -> belongsTo(orgao::class, 'orgao', 'id_orgao');
    }

}
