<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
    //kdolのWebhook
    Route::post('/webhook/user', 'KdolWebhookController@user');
    //chashback
    Route::post('/webhook/chashback', 'KdolWebhookController@chashback');

    //連携・連携解除ポーリング用API
    Route::get('/account_check/{user_key}/{session_key}', 'KdolController@account_check');