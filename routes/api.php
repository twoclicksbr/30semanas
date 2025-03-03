<?php

use App\Http\Controllers\CredentialController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\PersonAddressController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonUserController;
use App\Http\Controllers\TypePersonController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\VerifyHeaders;

// Agrupa as rotas que utilizam o middleware VerifyHeaders
Route::middleware([VerifyHeaders::class])->group(function () {

    // Rotas de autenticação
    Route::post('/login', [LoginController::class, 'login']); // Rota para login
    Route::post('/logout', [LogoutController::class, 'logout']); // Rota para logout

    // Rotas para Credential
    Route::get('/credential', [CredentialController::class, 'index']); // Lista todas as credenciais
    Route::get('/credential/{credential}', [CredentialController::class, 'show']); // Mostra uma credencial específica
    Route::post('/credential', [CredentialController::class, 'store']); // Cria uma nova credencial
    Route::put('/credential/{credential}', [CredentialController::class, 'update']); // Atualiza uma credencial existente
    Route::delete('/credential/{credential}', [CredentialController::class, 'destroy']); // Deleta uma credencial

    // Rotas para Person
    Route::get('/person', [PersonController::class, 'index']); // Lista todas as pessoas
    Route::get('/person/{person}', [PersonController::class, 'show']); // Mostra uma pessoa específica
    Route::post('/person', [PersonController::class, 'store']); // Cria uma nova pessoa
    Route::put('/person/{person}', [PersonController::class, 'update']); // Atualiza uma pessoa existente
    Route::delete('/person/{person}', [PersonController::class, 'destroy']); // Deleta uma pessoa

    // Rotas para PersonAddress
    Route::get('/person_address', [PersonAddressController::class, 'index']); // Lista todos os endereços
    Route::get('/person_address/{person_address}', [PersonAddressController::class, 'show']); // Mostra um endereço específico
    Route::post('/person_address', [PersonAddressController::class, 'store']); // Cria um novo endereço
    Route::put('/person_address/{person_address}', [PersonAddressController::class, 'update']); // Atualiza um endereço existente
    Route::delete('/person_address/{person_address}', [PersonAddressController::class, 'destroy']); // Deleta um endereço

    // Rotas para PersonUser
    Route::get('/person_user', [PersonUserController::class, 'index']); // Lista todos os usuários
    Route::get('/person_user/{person_user}', [PersonUserController::class, 'show']); // Mostra um usuário específico
    Route::post('/person_user', [PersonUserController::class, 'store']); // Cria um novo usuário
    Route::put('/person_user/{person_user}', [PersonUserController::class, 'update']); // Atualiza um usuário existente
    Route::delete('/person_user/{person_user}', [PersonUserController::class, 'destroy']); // Deleta um usuário

    Route::get('/check_email/{id}', [PersonUserController::class, 'check_email']);

    // Rotas para Gender
    Route::get('/gender', [GenderController::class, 'index']); // Lista todos os gêneros
    Route::get('/gender/{gender}', [GenderController::class, 'show']); // Mostra um gênero específico
    Route::post('/gender', [GenderController::class, 'store']); // Cria um novo gênero
    Route::put('/gender/{gender}', [GenderController::class, 'update']); // Atualiza um gênero existente
    Route::delete('/gender/{gender}', [GenderController::class, 'destroy']); // Deleta um gênero

    // Rotas para Video
    Route::get('/video', [VideoController::class, 'index']); // Lista todos os videos
    Route::get('/video/{video}', [VideoController::class, 'show']); // Mostra um video específico
    Route::post('/video', [VideoController::class, 'store']); // Cria um novo video
    Route::put('/video/{video}', [VideoController::class, 'update']); // Atualiza um video existente
    Route::delete('/video/{video}', [VideoController::class, 'destroy']); // Deleta um video

    // Rotas para Partilha
    Route::get('/share', [ShareController::class, 'index']); // Lista todos os Partilhas
    Route::get('/share/{share}', [ShareController::class, 'show']); // Mostra uma Partilha específico
    Route::post('/share', [ShareController::class, 'store']); // Cria um novo Partilha
    Route::put('/share/{share}', [ShareController::class, 'update']); // Atualiza uma Partilha existente
    Route::delete('/share/{share}', [ShareController::class, 'destroy']); // Deleta uma Partilha

    // Rotas para TypePerson
    Route::get('/type_person', [TypePersonController::class, 'index']); // Lista todos os tipos de pessoa
    Route::get('/type_person/{type_person}', [TypePersonController::class, 'show']); // Mostra um tipo de pessoa específico
    Route::post('/type_person', [TypePersonController::class, 'store']); // Cria um novo tipo de pessoa
    Route::put('/type_person/{type_person}', [TypePersonController::class, 'update']); // Atualiza um tipo de pessoa existente
    Route::delete('/type_person/{type_person}', [TypePersonController::class, 'destroy']); // Deleta um tipo de pessoa

});

Route::get('/validate_email/{token}', [PersonUserController::class, 'validate_email']);