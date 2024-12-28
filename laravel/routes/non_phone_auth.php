<?php
// 着信認証
Route::get('/phones', 'PhonesController@index')->name('phones.index');
Route::post('/phones/init', 'PhonesController@postInit')->name('phones.init');
Route::get('/phones/init', 'PhonesController@getInit');
Route::post('/phones/auth', 'PhonesController@auth')->name('phones.auth');
