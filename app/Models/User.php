<?php

namespace App\Models;

use App\Notifications\PasswordReset;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = "adm";
    protected $primaryKey = 'indice_adm';

    protected $fillable = [
        'matricula',
        'nome',
        'email',
        'senha',
        'data_cadastro',
        'permissao',
        'posto',
        'acesso',
        'orgao',
        'uf',
        'municipio',
        'ativo',
        'cpf',
        'nome_guerra',
        'email_verified_at',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    public function findForPassport($username) 
    {
        return $this->where('email', $username)->first();
    }

    public function getAuthPassword()
    {
        return $this->attributes['senha'];
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordReset($token));
    }

    public function Atividades()
    {
        return $this -> hasMany(atividades::class, 'usuario', 'indice_adm');
    }

    public function Orgao()
    {
        return $this -> belongsTo(orgao::class, 'orgao', 'id_orgao');
    }

    public function configuracao()
    {
        return $this -> belongsTo(configuracao::class, 'orgao', 'orgao');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'senha',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

}
