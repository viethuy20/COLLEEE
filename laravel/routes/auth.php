<?php
// ログアウト
Route::get('/logout/', function () {
    \Auth::logout();
    \Cookie::queue(\Cookie::forget('cookie_login'));
    session()->invalidate();
    session()->regenerateToken();
    return redirect(route('website.index'));
})->name('logout');

// 口コミ
Route::post('/reviews/confirm/', 'ReviewsController@confirm')
    ->name('reviews.confirm');
Route::post('/reviews/store/', 'ReviewsController@store')
    ->name('reviews.store');
Route::get('/reviews/add_helpful/{review}', 'ReviewsController@addHelpful')
    ->where('review', '[0-9]+')
    ->name('reviews.add_helpful');

// ご意見箱
Route::post('/users/opinion', 'UsersController@opinion')
    ->name('users.opinion');

// お気に入り
Route::get('/users/program_list/{page?}', 'UsersController@programList')
    ->where('page', '[0-9]+')
    ->name('users.program_list');
Route::get('/users/add_program/{program}', 'UsersController@addProgram')
    ->where('program', '[0-9]+')
    ->name('users.add_program');
Route::get('/users/remove_program/{program}', 'UsersController@removeProgram')
    ->where('program', '[0-9]+')
    ->name('users.remove_program');
Route::get('/users/add_recipe/{recipe}', 'UsersController@addRecipe')
    ->where('recipe', '[0-9]+')
    ->name('users.add_recipe');
Route::get('/users/remove_recipe/{recipe}', 'UsersController@removeRecipe')
    ->where('recipe', '[0-9]+')
    ->name('users.remove_recipe');

Route::get('/users/recipe_list/{page?}', 'UsersController@recipeList')
    ->where('page', '[0-9]+')
    ->name('users.recipe_list');

// 会員登録
Route::get('/entries/debut/', function () {
    return view('entries.debut');
})->name('entries.debut');

// マイページ
Route::get('/users', 'UsersController@show')
    ->name('users.show');
// 基本情報変更
Route::get('/users/edit', function () {
    return view('users.edit');
})->name('users.edit');

// ユーザーGETページ
Route::get('/users/{action}', function ($action) {
    return view('users.'.$action);
})->where('action', '(edit_email|edit_tel|edit_password|edit_nickname|edit_prefecture|edit_email_setting|edit_line|edit_google|new_password)');
// ユーザーPOSTページ
Route::post('/users/edit_email', 'UsersController@editEmail')
    ->name('users.edit_email');
Route::post('/users/edit_tel', 'UsersController@editTel')
    ->name('users.edit_tel');
Route::post('/users/edit_password', 'UsersController@storePassword')
    ->name('users.edit_password');
Route::post('/users/edit_nickname', 'UsersController@storeNickname')
    ->name('users.edit_nickname');
Route::post('/users/edit_prefecture', 'UsersController@storePrefecture')
    ->name('users.edit_prefecture');
Route::post('/users/edit_email_setting', 'UsersController@storeEmailSetting')
    ->name('users.edit_email_setting');
Route::post('/users/edit_line', 'UsersController@editLine')
    ->name('users.edit_line');
    Route::post('/users/edit_google', 'UsersController@editGoogle')
    ->name('users.edit_google');
Route::post('/users/new_password', 'UsersController@newPassword')
    ->name('users.new_password');

// 獲得一覧
Route::get('/users/point_list/{type?}/{page?}', 'UsersController@pointList')
    ->where('type', '[12]')
    ->where('page', '[0-9]+')
    ->name('users.point_list');

// 交換一覧
Route::get('/users/exchange_list/{page?}', 'UsersController@exchangeList')
    ->where('page', '[0-9]+')
    ->name('users.exchange_list');
// 獲得予定一覧
Route::get('/users/reward_list/{page?}', 'UsersController@rewardList')
    ->where('page', '[0-9]+')
    ->name('users.reward_list');

// 誕生日ポイント受け取り
Route::get('/users/birthday/', 'UsersController@birthday')
    ->name('users.birthday');

// アンケート
Route::post('/questions/answer/', 'QuestionsController@answer')
    ->name('questions.answer');
Route::post('/questions/answer_message/', 'QuestionsController@answerMessage')
    ->name('questions.answer_message');

// 交換
Route::get('/exchanges', function () {
    return view('exchanges.index');
})->name('exchanges.index');

// 銀行
Route::get('/banks/', 'BanksController@index')
    ->name('banks.index');
Route::get('/banks/bank_list', 'BanksController@bankList')
    ->name('banks.bank_list');
Route::get('/banks/branch_list/{bank}', 'BanksController@branchList')
    ->where('bank', '[0-9]{4}')
    ->name('banks.branch_list');
Route::get('/banks/create_account/{bank?}/{bank_branch?}', 'BanksController@createAccount')
    ->where('bank', '[0-9]{4}')
    ->where('bank_branch', '[0-9]{3}')
    ->name('banks.create_account');
Route::get('/banks/select_account', 'BanksController@selectAccount')
    ->name('banks.select_account');
Route::post('/banks/confirm_transfer/', 'BanksController@postConfirmTransfer')
    ->name('banks.confirm_transfer');
Route::get('/banks/confirm_transfer/', 'BanksController@getConfirmTransfer');

// ギフトコード
$gift_code_list = App\Http\Controllers\GiftCodesController::getTypeList();
$gift_codes = implode('|', $gift_code_list);
Route::get('/gift_codes/{type}', 'GiftCodesController@index')
    ->where('type', $gift_codes)
    ->name('gift_codes.index');
Route::post('/gift_codes/confirm/{type}', 'GiftCodesController@postConfirm')
    ->where('type', $gift_codes)
    ->name('gift_codes.confirm');
Route::get('/gift_codes/confirm/{type}', 'GiftCodesController@getConfirm')
    ->where('type', $gift_codes);

// ドットマネー
Route::get('/dot_money/account', 'DotMoneyController@account')
    ->name('dot_money.account');
Route::get('/dot_money/', 'DotMoneyController@index')
    ->name('dot_money.index');
Route::get('/dot_money/oauth/', 'DotMoneyController@oauth')
    ->name('dot_money.oauth');
Route::get('/dot_money/setting/', 'DotMoneyController@setting')
    ->name('dot_money.setting');
Route::get('/dot_money/exchange/{number}', 'DotMoneyController@exchange')
    ->where('number', '[0-9]{16}')
    ->name('dot_money.exchange');
Route::post('/dot_money/confirm/', 'DotMoneyController@postConfirm')
    ->name('dot_money.confirm');
Route::get('/dot_money/confirm/', 'DotMoneyController@getConfirm');

// dポイント
Route::get('/d_point/account', 'DPointController@account')
    ->name('d_point.account');
Route::get('/d_point/', 'DPointController@index')
    ->name('d_point.index');
Route::get('/d_point/oauth/', 'DPointController@oauth')
    ->name('d_point.oauth');
Route::get('/d_point/setting/', 'DPointController@setting')
    ->name('d_point.setting');
Route::get('/d_point/exchange/{number}', 'DPointController@exchange')
    ->where('number', '[0-9]{12}')
    ->name('d_point.exchange');
Route::post('/d_point/confirm/', 'DPointController@postConfirm')
    ->name('d_point.confirm');
Route::get('/d_point/confirm/', 'DPointController@getConfirm');
Route::get('/d_point/confirm/', 'DPointController@getConfirm');

// Route::post('/d_point/confirm/', 'DPointController@postOauthConfirm')
//     ->name('d_point.confirm');
// Route::get('/d_point/confirm/', 'DPointController@getOauthConfirm');


Route::get('/d_point/oauth_confirm/', 'DPointController@oauthConfirm')
    ->name('d_point.oauth_confirm');
Route::post('/d_point/oauth_complete', 'DPointController@oauthComplete')
->name('d_point.oauth_complete');

// LINE Pay
// Route::get('/line_pay/', 'LinePayController@index')
//     ->name('line_pay.index');
// Route::get('/line_pay/oauth/', 'LinePayController@oauth')
//     ->name('line_pay.oauth');
// Route::get('/line_pay/exchange/{line_id}', 'LinePayController@exchange')
//     ->name('line_pay.exchange');
// Route::post('/line_pay/confirm/', 'LinePayController@postConfirm')
//     ->name('line_pay.confirm');
// Route::get('/line_pay/confirm/', 'LinePayController@getConfirm');

// ブログ登録
// Route::get('/users/blog', function () {
//     return view('users.blog');
// })->name('users.blog');
// Route::post('/users/blog', 'UsersController@storeBlog');

// 退会
Route::get('/withdrawals/', function () {
    return view('withdrawals.index');
})->name('withdrawals.index');
Route::post('/withdrawals/confirm/', 'WithdrawalsController@confirm')
    ->name('withdrawals.confirm');
Route::post('/withdrawals/store/', 'WithdrawalsController@store');



// Fancrewアカウント作成
Route::post('/fancrew_accounts/create', 'FancrewController@createAccount')
    ->name('fancrew_accounts.create');

// cancel line
Route::get('line/cancel', 'UsersController@cancelLine')->name('line.cancel');
// cancel google
Route::get('google/cancel', 'UsersController@cancelGoogle')->name('google.cancel');

//receipt
Route::get('/receipt/list/{sort?}/{page?}/', 'ReceiptController@index')
    ->name('receipt.list');

// question
Route::get('/api/user/{user_id}/items', 'SurveyController@getSurveyFromCeres');
Route::get('/api/user/{user_id}/i_bridge_items', 'SurveyController@getSurveyFromIBridge');
Route::get('/questions/', 'QuestionsController@getList')->name('questions.index');



// digital gift Paypal
Route::get('/paypal/', 'DigitalGiftPaypalController@index')
    ->name('paypal.index');
Route::get('/paypal/exchange/', 'DigitalGiftPaypalController@exchange')
    ->name('paypal.exchange');
Route::post('/paypal/confirm/', 'DigitalGiftPaypalController@postConfirm')
    ->name('paypal.confirm');
Route::get('/paypal/confirm/', 'DigitalGiftPaypalController@getConfirm');

// digital gift JAL mile
Route::get('/jalmile/', 'DigitalGiftJalMileController@index')
    ->name('jalmile.index');
Route::get('/jalmile/exchange/', 'DigitalGiftJalMileController@exchange')
    ->name('jalmile.exchange');
Route::post('/jalmile/confirm/', 'DigitalGiftJalMileController@postConfirm')
    ->name('jalmile.confirm');
Route::get('/jalmile/confirm/', 'DigitalGiftJalMileController@getConfirm');


// paypayポイント
Route::get('/paypay/account', 'PaypayController@account')
    ->name('paypay.account');
Route::get('/paypay/', 'PaypayController@index')
    ->name('paypay.index');
Route::get('/paypay/oauth/', 'PaypayController@oauth')
    ->name('paypay.oauth');
Route::get('/paypay/setting/', 'PaypayController@setting')
    ->name('paypay.setting');
Route::get('/paypay/exchange/{number}', 'PaypayController@exchange')
    //->where('number', '[0-9]{12}')
    ->name('paypay.exchange');
Route::post('/paypay/confirm/', 'PaypayController@postConfirm')
    ->name('paypay.confirm');
Route::get('/paypay/confirm/', 'PaypayController@getConfirm');
Route::get('/paypay/confirm/', 'PaypayController@getConfirm');

// Route::post('/paypay/confirm/', 'PaypayController@postOauthConfirm')
//     ->name('paypay.confirm');
// Route::get('/paypay/confirm/', 'PaypayController@getOauthConfirm');


Route::get('/paypay/oauth_confirm/', 'PaypayController@oauthConfirm')
    ->name('paypay.oauth_confirm');
Route::get('/paypay/oauth_complete', 'PaypayController@oauthComplete')
->name('paypay.oauth_complete');



// KDOLハート
Route::get('/kdol/account', 'KdolController@account')
    ->name('kdol.account');
Route::get('/kdol/', 'KdolController@index')
    ->name('kdol.index');
Route::get('/kdol/oauth/', 'KdolController@oauth')
    ->name('kdol.oauth');
Route::get('/kdol/setting/', 'KdolController@setting')
    ->name('kdol.setting');
Route::get('/kdol/exchange/', 'KdolController@exchange')
    //->where('number', '[0-9]{12}')
    ->name('kdol.exchange');
Route::post('/kdol/confirm/', 'KdolController@postConfirm')
    ->name('kdol.confirm');
Route::get('/kdol/confirm/', 'KdolController@getConfirm');
Route::get('/kdol/confirm/', 'KdolController@getConfirm');

// Route::post('/kdol/confirm/', 'KdolController@postOauthConfirm')
//     ->name('kdol.confirm');
// Route::get('/kdol/confirm/', 'KdolController@getOauthConfirm');


Route::get('/kdol/oauth_confirm/', 'KdolController@oauthConfirm')
    ->name('kdol.oauth_confirm');
Route::get('/kdol/oauth_complete', 'KdolController@oauthComplete')
->name('kdol.oauth_complete');
//連携解除
Route::get('/kdol/release/', 'KdolController@release')
->name('kdol.release');
Route::get('/kdol/release_confirm/', 'KdolController@releaseConfirm')
->name('kdol.release_confirm');
Route::get('/kdol/release_complete/', 'KdolController@releaseComplete')
->name('kdol.release_complete');

