<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postos extends Model
{
    use HasFactory;

    protected $table = "posto_grad";
    protected $primaryKey = 'id_posto';

}
