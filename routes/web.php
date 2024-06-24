<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegistrationController;

Route::get('/', function () {
    session_start();
    if(isset($_SESSION["account"])){
        return redirect('/home');
    } else {
        return view('Login');
    }
});

Route::get('/images/{filename}', function($filename){
    $path = resource_path().'/views/images/'.$filename;
    if(!File::exists($path)) {
        return abort(404);
    }
    $file = File::get($path);
    $type = File::mimeType($path);
    $response = Response::make($file, 200);
    $response->header("Content-Type", $type);
    return $response;
});

Route::post('/login/authentication', [LoginController::class, 'authentication']);

Route::get('/get_accounts', [LoginController::class, 'get_accounts']);

Route::post('/register/registration', [RegistrationController::class, 'registration']);