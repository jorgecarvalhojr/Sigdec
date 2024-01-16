<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class analisesController extends Controller
{
    public function index()
    {
        return \view('auth.analises_bi');
    }

    public function prodec()
    {
        return \view('auth.prodec_bi');
    }

    public function sigdec()
    {
        return \view('auth.sigdec_bi');
    }

    public function plancon()
    {
        return \view('auth.plancon_bi');
    }

    public function diagnose()
    {
        return \view('auth.diagnose.diagnose_bi');
    }
}
