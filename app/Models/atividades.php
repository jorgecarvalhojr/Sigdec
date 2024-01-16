<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class atividades extends Model
{
    use HasFactory;

    protected $table = "atividades_dc";
    protected $primaryKey = 'id_atv';
    public $timestamps = false;

    protected $fillable = [
        'tipo_atividade',
        'ciclo',
        'titulo',
        'orgao',
        'promotor',
        'autoria',
        'municipio',
        'op_viatura',
        'viatura',
        'kilometragem',
        'fide',
        'ecp',
        'se',
        'cobrade',
        'relatorio_foto',
        'relato',
        'data_inicio',
        'data_fim',
        'data_cadastro',
        'usuario',
        'id_tema',
        'num_fide',
        'num_se',
        'num_ecp',
        'arquivos',
        'bol',
        'boletim',
    ];

    public function User()
    {
        return $this -> belongsTo(User::class, 'usuario', 'indice_adm');
    }

    public function TipoAtividade()
    {
        return $this -> belongsTo(lista_atividades::class, 'tipo_atividade', 'id_atividade');
    }

    public function Orgao()
    {
        return $this -> belongsTo(orgao::class, 'orgao', 'id_orgao');
    }

    public function Promotor()
    {
        return $this -> belongsTo(orgao::class, 'promotor', 'id_orgao');
    }

    public function ViaturasU()
    {
        return $this -> hasMany(op_viatura::class, 'id_atv', 'id_atv');
    }

    public function Cobrade()
    {
        return $this -> belongsTo(cobrade::class, 'cobrade', 'cobrade');
    }
}
