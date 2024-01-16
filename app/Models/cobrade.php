<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cobrade extends Model
{
    use HasFactory;
    protected $table = 'cobrade';
    protected $primaryKey = 'cobrade';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    public function Atividades()
    {
        return $this->hasMany(atividades::class, 'cobrade', 'cobrade');
    }

}
