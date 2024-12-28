<?php
// 銀行
Route::post('/banks/store_transfer/', 'BanksController@storeTransfer')
    ->name('banks.store_transfer');

// ギフトコード
$gift_code_list = App\Http\Controllers\GiftCodesController::getTypeList();
$gift_codes = implode('|', $gift_code_list);
Route::post('/gift_codes/store/{type}', 'GiftCodesController@store')
    ->where('type', $gift_codes)
    ->name('gift_codes.store');

// ドットマネー
Route::post('/dot_money/store/', 'DotMoneyController@store')
    ->name('dot_money.store');

// dポイント
Route::post('/d_point/store/', 'DPointController@store')
    ->name('d_point.store');

// LINE Pay
// Route::post('/line_pay/store/', 'LinePayController@store')
//     ->name('line_pay.store');



        // DIGITAL GIFT PayPal
Route::post('/paypal/store/', 'DigitalGiftPaypalController@store')
    ->name('paypal.store');

// DIGITAL GIFT JAL mile
Route::post('/jalmile/store/', 'DigitalGiftJalMileController@store')
    ->name('jalmile.store');


// PayPay
Route::post('/paypay/store/', 'PaypayController@store')
->name('paypay.store');

// KDOL
Route::post('/kdol/store/', 'KdolController@store')
->name('kdol.store');

