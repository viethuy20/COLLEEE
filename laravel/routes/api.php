<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// Programテーブルを検索するAPI
Route::prefix('/program')->name('api.program.')->group(function () {
    Route::middleware(['api_key'])->get('/search', 'Api\ProgramController@search')
        ->name('search');
});

// Route api login
Route::prefix('/connect/net-manga')->name('api.login.')->group(function () {
    Route::middleware(['web'])->group(function () {
        Route::get('/confirm', 'Api\LoginController@confirm')
            ->name('confirm');

        Route::get('/redirect', 'Api\LoginController@redirect')
            ->name('redirect');

        Route::get('/login', 'Api\LoginController@login')
            ->name('login');

        Route::get('/logout', 'Api\LoginController@logout')
            ->name('logout');

        Route::post('/login', 'Api\LoginController@submit')
            ->name('submit');

        Route::get('/reset', 'Api\LoginController@reset')
            ->name('reset');
    });

    Route::post('/perform', 'Api\LoginController@perform')
        ->name('perform');

    Route::get('/use-another-account', 'Api\LoginController@useAnotherAccount')
        ->name('another_account');
});