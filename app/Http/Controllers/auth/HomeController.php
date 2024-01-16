<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\atividades;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('auth.home');
    }

    public function home()
    {
        return view('auth.diagnose_home');
    }

    public function hoje()
    {
        $atividades = atividades::where(DB::raw("DATE_FORMAT(data_inicio, '%Y-%m-%d')"), '=', date('Y-m-d'))->orderBy('titulo')->get();
        return view('auth.atividades_dia')->with('atividades', $atividades);
    }
}
