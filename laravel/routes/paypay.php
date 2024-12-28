<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
    //PayPayのWebhook
    Route::post('/webhook/user', 'PaypayWebhookController@user');