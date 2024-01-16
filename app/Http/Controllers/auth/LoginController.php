<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class LoginController extends Controller
{

    public function index()
    {
        return view('login');
    }

    public function auth(Request $request)
    {
        
        $request -> validate([

            'email' => 'required|email',
            'password' => 'required',
        ]); 

        $credentials = [
            'email' => $request ->email, 
            'password' => $request->password, 
        ];

        if (Auth::attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            if(Auth::user()-> ativo == 'Não')
            {
                Session::flush();

                Auth::logout();
        
                return redirect()->route('acesso.index') -> with(['warning' => 'Usuário Inativado!']);
        
            }
            return redirect()->intended('home');
        }
        else{
            return view('login')->with([
                'error' => 'Credenciais inválidas!',
            ]);
        }
    }

}
