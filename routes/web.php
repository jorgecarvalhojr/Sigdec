<?php

use App\Http\Controllers\auth\analisesController;
use App\Http\Controllers\auth\atv_dcController;
use App\Http\Controllers\auth\ConfigController;
use App\Http\Controllers\auth\diagnose\diagnoseController;
use App\Http\Controllers\auth\diagnose\edicoesController;
use App\Http\Controllers\auth\diagnose\gruposController;
use App\Http\Controllers\auth\diagnose\opcoesController;
use App\Http\Controllers\auth\diagnose\questoesController;
use App\Http\Controllers\auth\estatisticasController;
use App\Http\Controllers\auth\HomeController;
use App\Http\Controllers\auth\LoginController;
use App\Http\Controllers\auth\LogoutController;
use App\Http\Controllers\auth\orgaoController;
use App\Http\Controllers\auth\PasswordController;
use App\Http\Controllers\auth\registrosController;
use App\Http\Controllers\auth\relatoriosController;
use App\Http\Controllers\auth\UserController;
use App\Http\Controllers\auth\viaturaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', [LoginController::class, 'index'])-> name('acesso.index');
Route::post('/login', [LoginController::class, 'auth'])-> name('login.auth');

Route::get('/forgot-password', [PasswordController::class, 'request'])->middleware('guest')->name('password.request');
Route::post('/forgot-password', [PasswordController::class, 'email'])->middleware('guest')->name('password.email');
Route::get('/reset-password/{token}', [PasswordController::class, 'reset'])->middleware('guest')->name('password.reset');
Route::post('/reset-password', [PasswordController::class, 'update'])->middleware('guest')->name('password.update');

Route::group(['middleware' => 'auth'], function() {

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/inicio', [HomeController::class, 'hoje'])-> name('inicio');
    Route::get('/diagnose', [HomeController::class, 'home'])-> name('diagnose.home');

    Route::get('/diagone/grupo', [gruposController::class, 'index'])-> name('d.grupo.index');
    Route::get('/diagone/grupo/edit/{id}', [gruposController::class, 'edit'])-> name('d.grupo.edit');
    Route::post('/diagone/grupo/edit/{id}', [gruposController::class, 'update'])-> name('d.grupo.update');
    Route::post('/diagone/grupo/create', [gruposController::class, 'create'])-> name('d.grupo.create');

    Route::get('/diagone/questao', [questoesController::class, 'index'])-> name('d.questao.index');
    Route::get('/diagone/questao/edit/{id}', [questoesController::class, 'edit'])-> name('d.questao.edit');
    Route::post('/diagone/questao/edit/{id}', [questoesController::class, 'update'])-> name('d.questao.update');
    Route::post('/diagone/questao/create', [questoesController::class, 'create'])-> name('d.questao.create');

    Route::get('/diagone/opcao/{idq}', [opcoesController::class, 'index'])-> name('d.opcao.index');
    Route::get('/diagone/opcao/edit/{id}', [opcoesController::class, 'edit'])-> name('d.opcao.edit');
    Route::post('/diagone/opcao/edit/{id}', [opcoesController::class, 'update'])-> name('d.opcao.update');
    Route::post('/diagone/opcao/create/{id}', [opcoesController::class, 'create'])-> name('d.opcao.create');

    Route::get('/diagone/edicao', [edicoesController::class, 'index'])-> name('d.edicao.index');
    Route::get('/diagone/edicao/edit/{id}', [edicoesController::class, 'edit'])-> name('d.edicao.edit');
    Route::post('/diagone/edicao/edit/{id}', [edicoesController::class, 'update'])-> name('d.edicao.update');
    Route::post('/diagone/edicao/create', [edicoesController::class, 'create'])-> name('d.edicao.create');

    Route::get('/diagone/edicoes', [diagnoseController::class, 'edicaoShow'])-> name('diagnose.edicaoShow');
    Route::get('/diagone/municipios/{id}', [diagnoseController::class, 'municipios'])-> name('diagnose.municipios');
    Route::get('/diagone/municipio/{ide}/{idm}', [diagnoseController::class, 'municipio'])-> name('diagnose.municipio');
    Route::get('/diagone/questoes/{ide}/{idm}/{idg}', [diagnoseController::class, 'questoes'])-> name('diagnose.questoes');
    Route::get('/diagone/resposta/{ide}/{idm}/{idq}', [diagnoseController::class, 'responder'])-> name('diagnose.responder');
    Route::post('/diagone/resposta/create', [diagnoseController::class, 'create'])-> name('diagnose.create');
    Route::post('/diagone/resposta/edit/{id}', [diagnoseController::class, 'edit'])-> name('diagnose.edit');

    Route::get('/diagnose/analises', [analisesController::class, 'diagnose'])-> name('analises.diagnose');

    Route::get('/analises', [analisesController::class, 'index'])-> name('analises.index');
    Route::get('/analises/prodec', [analisesController::class, 'prodec'])-> name('analises.prodec');
    Route::get('/analises/sigdec', [analisesController::class, 'sigdec'])-> name('analises.sigdec');
    Route::get('/analises/plancon', [analisesController::class, 'plancon'])-> name('analises.plancon');

    Route::get('/sair', [LogoutController::class, 'perform'])->name('sair');

    Route::get('/config', [ConfigController::class, 'index'])->name('config.index');
    Route::post('/config', [configController::class, 'create'])->name('config.create');
    Route::post('/config/{id}', [configController::class, 'update'])->name('config.update');

    Route::get('/atv_dc/index', [atv_dcController::class, 'index'])->middleware('auth') ->name('atv_dc.index');
    Route::get('/atv_dc/edit/{id}', [atv_dcController::class, 'edit'])->middleware('auth') ->name('atv_dc.edit');
    Route::post('/atv_dc/update/{id}', [atv_dcController::class, 'update'])->middleware('auth') ->name('atv_dc.update');
    Route::post('/atv_dc/create', [atv_dcController::class, 'create'])->middleware('auth') ->name('atv_dc.create');
    Route::get('/atv_dc/download/{id}', [atv_dcController::class, 'download'])->middleware('auth') ->name('atv_dc.download');
    Route::get('/atv_dc/del/{id}/{img}', [atv_dcController::class, 'delete'])->middleware('auth') ->name('atv_dc.delete');
    Route::get('/atv_dc/fotos/{id}', [atv_dcController::class, 'fotos'])->middleware('auth') ->name('atv_dc.fotos');

    Route::get('users/show', [UserController::class, 'show'])-> middleware('auth') ->name('users.show');
    Route::get('user/edit/{id}', [UserController::class, 'edit'])-> middleware('auth') ->name('user.edit');
    Route::post('user/update/{id}', [UserController::class, 'update'])-> middleware('auth') ->name('user.update');
    Route::get('user/index', [UserController::class, 'index'])->middleware('auth') ->name('user.index');
    Route::post('user/create', [UserController::class, 'create'])-> middleware('auth') ->name('user.create');

    Route::get('viatura/index', [viaturaController::class, 'index'])->middleware('auth') ->name('viatura.index');
    Route::get('viatura/edit/{id}', [viaturaController::class, 'edit'])->middleware('auth') ->name('viatura.edit');
    Route::post('viatura/update/{id}', [viaturaController::class, 'update'])->middleware('auth') ->name('viatura.update');
    Route::post('viatura/create', [viaturaController::class, 'create'])->middleware('auth') ->name('viatura.create');

    Route::get('orgao/index', [orgaoController::class, 'index'])->middleware('auth') ->name('orgao.index');
    Route::get('orgao/edit/{id}', [orgaoController::class, 'edit'])->middleware('auth') ->name('orgao.edit');
    Route::post('orgao/update/{id}', [orgaoController::class, 'update'])->middleware('auth') ->name('orgao.update');
    Route::post('orgao/create', [orgaoController::class, 'create'])->middleware('auth') ->name('orgao.create');

    Route::get('registros/index', [registrosController::class, 'index'])->middleware('auth') ->name('registros.index');
    Route::get('regsitros/show/{id}', [registrosController::class, 'show'])->middleware('auth') ->name('registros.show');

    Route::get('estatisticas/', [estatisticasController::class, 'index'])-> middleware('auth') ->name('estatisticas.index');
    Route::post('estatisticas/show', [estatisticasController::class, 'show'])-> middleware('auth') ->name('estatisticas.show');
    Route::get('estatisticas/export1/{data_inicial}/{data_final}/{orgao}', [estatisticasController::class, 'export_km'])-> middleware('auth') ->name('export_km');
    Route::get('estatisticas/export2/{data_inicial}/{data_final}/{orgao}', [estatisticasController::class, 'export_atividades'])-> middleware('auth') ->name('export_atividades');
    Route::get('estatisticas/export3/{data_inicial}/{data_final}/{orgao}', [estatisticasController::class, 'export_ciclo'])-> middleware('auth') ->name('export_ciclo');

    Route::get('relatorios/', [relatoriosController::class, 'index'])-> middleware('auth') ->name('relatorios.index');
    Route::post('relatorios/show', [relatoriosController::class, 'show'])-> middleware('auth') ->name('relatorios.show');
    Route::get('relatorios/export1/{data_inicial}/{data_final}/{orgao}', [relatoriosController::class, 'export_xls_atividades'])-> middleware('auth') ->name('export_xls');
    Route::get('relatorios/export2/{data_inicial}/{data_final}/{orgao}', [relatoriosController::class, 'export_pdf_atividades'])-> middleware('auth') ->name('export_pdf');

    
    });