<?php

use App\Services\Line\LineService;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
})->name('website.index');
*/
Route::get('/', function () {
    return view('index');
})->name('website.index')->middleware('check.popup');

Route::post('/set_dont_show_today', function (Request $request) {
    $userId = Auth::user()->id;
    $dontShowTodayKey = 'dont_show_today_' . $userId;
    $now = Carbon\Carbon::now();
    $endOfDay = Carbon\Carbon::now()->endOfDay();
    $minutesUntilMidnight = $now->diffInMinutes($endOfDay);

    // create cookie `dont_show_today`
    return response()->json(['success' => true])->cookie($dontShowTodayKey, true, $minutesUntilMidnight); 
})->name('set_dont_show_today');

Route::post('/clear_dont_show_today', function (Request $request) {
    $userId = Auth::user()->id;
    $dontShowTodayKey = 'dont_show_today_' . $userId;

    // clear cookie `dont_show_today`
    return response()->json(['success' => true])->cookie($dontShowTodayKey, '', -1);
})->name('clear_dont_show_today');


Route::get('/qr/image', function () {
    if (!request()->has('d')) {
        abort(404, 'Not Found.');
    }
    $d = request()->input('d');
    $s = request()->input('s', 300);

    $m = floor($s / 30);

    // Create a basic QR code
    $qrCode = new \Endroid\QrCode\QrCode($d);
    $qrCode->setSize($s);

    // Set advanced options
    $qrCode->setWriterByName('png');
    $qrCode->setMargin($m);
    $qrCode->setEncoding('UTF-8');
    $qrCode->setErrorCorrectionLevel(new \Endroid\QrCode\ErrorCorrectionLevel(
        \Endroid\QrCode\ErrorCorrectionLevel::HIGH
    ));
    $qrCode->setForegroundColor(['r' => 0, 'g' => 0, 'b' => 0]);
    $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255]);
    $qrCode->setValidateResult(false);

    // Directly output the QR code
    return response($qrCode->writeString(), 200)
        ->header('Content-Type', $qrCode->getContentType());
})->name('qr.image');

Route::get('/error/', function () {
    return view('errors.index');
})->name('error');

Route::match(['get', 'post'], '/sitemap_programs.xml', 'SitemapController@programs');
Route::match(['get', 'post'], '/sitemap_questions.xml', 'SitemapController@questions');

Route::get('entries/regist', function () {
    $referer = request()->input('referer');
    $base_url = config('app.url');
    // ドメイン確認
    if (isset($referer) && !Illuminate\Support\Str::startsWith($referer, $base_url)) {
        $referer = null;
    }
    return view('entries.regist', ['referer' => $referer]);
})->name('entries.regist');

Route::get('entries/about', function () {
    $referer = request()->input('referer');
    $base_url = config('app.url');
    // ドメイン確認
    if (isset($referer) && !Illuminate\Support\Str::startsWith($referer, $base_url)) {
        $referer = null;
    }
    return view('entries.about', ['referer' => $referer]);
})->name('entries.about');

// ログイン
Route::get('/login/{back?}', function (int $back = 1) {
    $referer = request()->input('referer');
    $base_url = config('app.url');
    $lineService = new LineService();
    $urlLine = $lineService->getLoginBaseUrl();
    // ドメイン確認
    if (isset($referer) && !Illuminate\Support\Str::startsWith($referer, $base_url)) {
        $referer = null;
    }
    // ドットマネーの場合
    $callback = request()->input('callback');
    if (isset($callback)) {
        $referer = \App\External\DotMoney::isDotMoneyUrl($callback) ? $callback : $referer;
    }
    if (!isset($referer)) {
        $referer = $back == 1 ? \URL::previous() : route('website.index');
        $info = parse_url($base_url);
        if (is_secure() && Illuminate\Support\Str::startsWith($referer, 'http://'.$info['host'])) {
            $referer = 'https'.substr($referer, 4);
        }
    }
    return view('login', ['referer' => $referer, 'urlLine' => $urlLine]);
})->where('back', '0')
    ->name('login');
Route::post('/login/', 'UsersController@login')->middleware('recaptcha');

Route::get('/programs/list/{sort?}/{page?}/', 'ProgramsController@search')
    ->where('sort', '[0-9]+')
    ->where('page', '[0-9]+')
    ->name('programs.list');

Route::get('/programs/{program}/{rid?}/', 'ProgramsController@show')
    ->where('program', '[0-9]+')
    ->where('rid', '[a-zA-Z0-9]+')
    ->name('programs.show');
// クリック発生
Route::get('/programs/click/{program}/{rid?}/', 'ProgramsController@click')
    ->where('program', '[0-9]+')
    ->where('rid', '[a-zA-Z0-9]+')
    ->name('programs.click');
Route::post('/programs/click/{program}/{rid?}/', 'ProgramsController@click')
    ->where('program', '[0-9]+')
    ->where('rid', '[a-zA-Z0-9]+');

// アンケート
Route::get('/questions/list', 'QuestionsController@getMyList')
    ->name('questions.my_list');
Route::get('/questions/list/{page}', 'QuestionsController@getList')
    ->where('page', '[0-9]+')
    ->name('questions.list');
Route::get('/questions/{question}/', 'QuestionsController@show')
    ->where('question', '[0-9]+')
    ->name('questions.show');
Route::get('/questions/monthly/{target}/', 'QuestionsController@monthly')
    ->where('target', '[0-9]{6}')
    ->name('questions.monthly');
Route::get('/questions/ajax_message/{question}/{limit?}/', 'QuestionsController@ajaxMessage')
    ->where('question', '[0-9]+')
    ->where('limit', '[0-9]+')
    ->name('questions.ajax_message');

// 口コミ
Route::get(
    '/reviews/program/{program}/{all?}/{sort?}/{limit?}/',
    function (int $program_id, int $all = 0, int $sort = 0, int $limit = 5) {
        return view('elements.program_review_list', [
            'for_ajax' => true,
            'condition'=> (object) ['program_id' => $program_id, 'all' => $all, 'sort' => $sort, 'limit' => $limit]]);
    }
)->where('program', '[0-9]+')
    ->where('all', '[01]')
    ->where('sort', '[012]')
    ->where('limit', '[0-9]+')
    ->name('reviews.ajax_program');
Route::get('/reviews/reviewer/{user}/{sort?}', 'ReviewsController@reviewer')
    ->where('user', '[0-9]+')
    ->where('sort', '[012]')
    ->name('reviews.reviewer');
Route::get('/reviews/ajax_reviewer/{user}/{sort?}/', 'ReviewsController@ajaxReviewer')
    ->where('user', '[0-9]+')
    ->where('sort', '[012]')
    ->name('reviews.ajax_reviewer');


// 会員登録
Route::post('/entries/send', 'EntriesController@postSend')
    ->name('entries.send');
Route::get('/entries/create/{email_token_id?}/', 'EntriesController@create')
    ->name('entries.create');
Route::post('/entries/confirm/', 'EntriesController@postConfirm')->name('entries.post_confirm');
Route::get('/entries/confirm/', 'EntriesController@getConfirm')
    ->name('entries.confirm');
Route::get('/entries/confirm_tel/', 'EntriesController@confirmTel')
    ->name('entries.confirm_tel');
Route::get('/entries/auth_tel/', 'EntriesController@authTel')
    ->name('entries.auth_tel');
Route::get('/entries/question/', 'EntriesController@question')
    ->name('entries.question');
Route::post('/entries/store/', 'EntriesController@store')
    ->name('entries.store');

Route::get('/api/check_auth_tel/{token}/{p_tel}/', 'EntriesController@apiCheckAuthTel');

//login with line
Route::get('/entries/create/{token_id}/', 'EntriesController@createRegist')
    ->name('entries.create.line');
// Callback url with line
Route::get('line/login/callback', 'UsersController@handleLineCallback')->name('login.line.callback');

//google login
Route::get('/google', 'UsersController@createRegistGoogle')
    ->name('users.create.google');
Route::get('/google/callback', 'UsersController@callbackGoogle')->name('login.google.callback');

// メールアドレス変更
Route::get('/users/confirm_email/{email_token_id}/', 'UsersController@confirmEmail')
    ->name('users.confirm_email');
Route::post('/users/store_email/', 'UsersController@storeEmail')
    ->name('users.store_email');
// 電話番号
Route::get('/users/confirm_tel/{email_token_id}/', 'UsersController@confirmTel')
    ->name('users.confirm_tel');
Route::post('/users/store_tel/', 'UsersController@storeTel')
    ->name('users.store_tel');

//
Route::get('/friends', 'FriendsController@index')->name('friends.index');

// 会員規約,Fancrewガイド,会員ランク特典・条件,退会
$simple_get_page_list = ['abouts.membership_contract', 'abouts.fancrew', 'abouts.member_rank',
    'shops.index', 'entries.index', 'reminders.index', 'sitemaps.index', 'withdrawals.store'];
foreach ($simple_get_page_list as $simple_get_page) {
    $data = explode('.', $simple_get_page);
    if (Illuminate\Support\Arr::last($data) == 'index') {
        array_pop($data);
    }
    Route::get('/'.implode('/', $data), function () use ($simple_get_page) {
        return view($simple_get_page);
    })->name($simple_get_page);
}

// Fancrew
Route::get('/fancrew/{action?}{ext?}', 'FancrewController@index')
    ->where('action', '(smartphone\.pages|pages)')
    ->where('ext', '\.php')
    ->name('fancrew.pages');

// リマインダー
Route::post('/reminders/confirm/', 'RemindersController@confirm')
    ->name('reminders.confirm');
Route::post('/reminders/send/', 'RemindersController@send')
    ->name('reminders.send');
Route::get('/reminders/auth/{email_token_id}/', 'RemindersController@password');//※一定期間後除去
Route::get('/reminders/password/{email_token_id}', 'RemindersController@password')
    ->name('reminders.password');
Route::post('/reminders/store/', 'RemindersController@store')
    ->name('reminders.store');

Route::get('/reminders/email/{email_token_id}', 'RemindersController@email')
    ->name('reminders.email');
Route::post('/reminders/store_email/', 'RemindersController@storeEmail')
    ->name('reminders.store_email');

// 問い合わせ
Route::get('/inquiries/{inquiry_id?}/', 'InquiriesController@inquiry')
    ->where('inquiry_id', '[0-9]+')
    ->name('inquiries.index');
Route::post('/inquiries/confirm/', 'InquiriesController@confirm')
    ->name('inquiries.confirm');
Route::post('/inquiries/store/', 'InquiriesController@store')
    ->name('inquiries.store');
Route::get('/inquiries/ajax_meta_cv_api_post/', 'InquiriesController@ajaxMetaCvApiPost');

// 特集
Route::get('/features/', 'FeaturesController@index')
    ->name('features.index');
Route::get('/features/{feature_id}/', 'FeaturesController@show')
    ->where('feature_id', '[0-9]+')
    ->name('features.show');

// クレジットカード比較
Route::get('/credit_cards/', function () {
    $device_id = \App\Device\Device::getDeviceId();

    $application_json = '';
    $position = 1;
    $meta = new App\Services\Meta();
    $arr_breadcrumbs = $meta->setBreadcrumbs(null);
    foreach($arr_breadcrumbs as $key => $val) {
        $application_json .= '{"@type": "ListItem","position":' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
        $position++;
    }
    $link = route('credit_cards.index');
    $application_json .= '{"@type": "ListItem","position":' . $position . ', "name": "クレジットカード徹底比較", "item": "' . $link . '"}';
    return $device_id == 1 ? redirect(route('credit_cards.list')) : view('credit_cards.index', ['application_json' => $application_json]);
})->name('credit_cards.index');
Route::get('/credit_cards/list/{sort?}/{page?}/', 'CreditCardsController@search')
    ->where('sort', '[0-9]+')
    ->where('page', '[0-9]+')
    ->name('credit_cards.list');

// Ajax
Route::get('/ajax/user/', 'AjaxController@user');
Route::get('/ajax/recipe_list/', 'AjaxController@recipeList');
Route::get('/ajax/service_list/', 'AjaxController@serviceList');
Route::get('/ajax/feature_list/', 'AjaxController@featureList');

// Logrecoai
Route::post('/recommend/history/view', 'RecommendController@historyView');
Route::post('/recommendwp/history/view', 'RecommendController@historyView');
Route::post('/recommend/kpi/click', 'RecommendController@kpiClick');
Route::post('/recommendwp/kpi/click', 'RecommendController@kpiClick');
Route::post('/recommendwp/articles', 'RecommendController@getRecommendArticles');
Route::post('/recommendwp/programs', 'RecommendController@getRecommendPrograms');

// Oauth認証
Route::get('/oauth', function () {
    return redirect('https://colleee.net/support/?p=1254');
});

Route::get('/beginners', function() {
    return view('beginners');
})->name('beginners');

Route::get('/stops', function() {
    return view('stops');
})->name('stops');

Route::get('/skyflag', function() {
    $device_id = \App\Device\Device::getDeviceId();
    return $device_id == 1 ? view('skyflag.mainte') : view('skyflag.about');
})->name('skyflag.about');

Route::get('/greeadsreward', function() {
    $device_id = \App\Device\Device::getDeviceId();
    return $device_id == 1 ? view('greeadsreward.mainte') : view('greeadsreward.about');
})->name('greeadsreward.about');

Route::get('/welkatsu', function() {
    $is_mobile = \App\Device\Device::isMobile();
    // SPだけ表示
    if ($is_mobile) {
        return view('welkatsu');
    }
    abort(404);
})->name('welkatsu');


Route::get('/gmotech', function() {
    $device_id = \App\Device\Device::getDeviceId();
    return $device_id == 1 ? view('gmotech.mainte') : view('gmotech.about');
})->name('gmotech.about');

Route::get('/mychips', function() {
    $device_id = \App\Device\Device::getDeviceId();
    return $device_id == 1 ? view('mychips.index') : view('mychips.mobile');
})->name('mychips.about');

// 掲載期間が終了したら削除する
Route::get('/special_programs/20231003', 'SpecialProgramController@index')
    ->name('special_programs');

    //  クリックでゲット
Route::get('/sp_programs/', 'SpProgramsController@index')
->name('sp_programs.index');
Route::get('/sp_programs/click/{sp_program}/', 'SpProgramsController@click')
->where('sp_program', '[0-9]+')
->name('sp_programs.click');
//
Route::get('/asps/click/{asp}', 'AspsController@click')
    ->where('asp', '[0-9]+')
    ->name('asps.click');

