<?php

use Illuminate\Support\Facades\Route;
use App\Services\SendEmailService;
use Illuminate\Support\Facades\Mail;

Route::get('/', function () {
    return view('welcome');

});
