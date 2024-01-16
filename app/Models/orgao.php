<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class orgao extends Model
{
    use HasFactory;

    protected $table = "orgao";
    protected $primaryKey = 'id_orgao';
    public $timestamps = false;

    protected $fillable = [
        'cod',
        'sigla',
        'descricao',
    ];

    public function Users()
    {
        return $this -> hasMany(User::class, 'orgao', 'id_orgao');
    }

    public function Atividades()
    {
        return $this -> hasMany(atividades::class, 'orgao', 'id_orgao');
    }

    public function Redec()
    {
        return $this -> hasMany(redec::class, 'id_orgao', 'id_orgao');
    }
}
