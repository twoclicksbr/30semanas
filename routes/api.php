<?php

use App\Http\Controllers\CredentialController;
use App\Http\Controllers\GenderController;
use App\Http\Controllers\PersonAddressController;
use App\Http\Controllers\PersonController;
use App\Http\Controllers\PersonUserController;
use App\Http\Controllers\TypePersonController;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\VerifyHeaders;

Route::middleware([VerifyHeaders::class])->group(function () {

    Route::get('/credential', [CredentialController::class, 'index']);
    Route::get('/credential/{credential}', [CredentialController::class, 'show']);
    Route::post('/credential', [CredentialController::class, 'store']);
    Route::put('/credential/{credential}', [CredentialController::class, 'update']);
    Route::delete('/credential/{credential}', [CredentialController::class, 'distroy']);

    Route::get('/person', [PersonController::class, 'index']);
    Route::get('/person/{person}', [PersonController::class, 'show']);
    Route::post('/person', [PersonController::class, 'store']);
    Route::put('/person/{person}', [PersonController::class, 'update']);
    Route::delete('/person/{person}', [PersonController::class, 'distroy']);

    Route::get('/person_address', [PersonAddressController::class, 'index']);
    Route::get('/person_address/{person_address}', [PersonAddressController::class, 'show']);
    Route::post('/person_address', [PersonAddressController::class, 'store']);
    Route::put('/person_address/{person_address}', [PersonAddressController::class, 'update']);
    Route::delete('/person_address/{person_address}', [PersonAddressController::class, 'distroy']);

    Route::get('/person_user', [PersonUserController::class, 'index']);
    Route::get('/person_user/{person_user}', [PersonUserController::class, 'show']);
    Route::post('/person_user', [PersonUserController::class, 'store']);
    Route::put('/person_user/{person_user}', [PersonUserController::class, 'update']);
    Route::delete('/person_user/{person_user}', [PersonUserController::class, 'distroy']);

    Route::get('/gender', [GenderController::class, 'index']);
    Route::get('/gender/{gender}', [GenderController::class, 'show']);
    Route::post('/gender', [GenderController::class, 'store']);
    Route::put('/gender/{gender}', [GenderController::class, 'update']);
    Route::delete('/gender/{gender}', [GenderController::class, 'distroy']);

    Route::get('/type_person', [TypePersonController::class, 'index']);
    Route::get('/type_person/{type_person}', [TypePersonController::class, 'show']);
    Route::post('/type_person', [TypePersonController::class, 'store']);
    Route::put('/type_person/{type_person}', [TypePersonController::class, 'update']);
    Route::delete('/type_person/{type_person}', [TypePersonController::class, 'distroy']);

});