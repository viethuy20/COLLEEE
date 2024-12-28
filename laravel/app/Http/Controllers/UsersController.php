<?php
namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\Rule;
use App\Services\Line\LineService;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Response;
use App\Device\Device;
use App\EmailToken;
use App\External\Recipe;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\Phone;
use App\LineAccount;
use App\Program;
use App\ReviewPointManagement;
use App\User;
use App\UserPoint;
use App\UserProgram;
use App\UserRecipe;
use App\UserReferralPointDetail;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class UsersController extends Controller
{
    protected $lineService;

    use ControllerTrait;

    /** ページャ件数. */
    const PC_LIMIT = 20;
    const SP_LIMIT = 10;

    const LOGIN_DEFAULT = 0;
    const LOGIN_LINE = 1;
    const LOGIN_GOOGLE = 2;

    /** session line access token*/
    const ENTRY_USER_SESSION_LINE_ACESS_TOKEN = 'entry_user_line_access_token';
    const ENTRY_USER_SESSION_GOOGLE_ACESS_TOKEN = 'entry_user_google_access_token';

    /** cookie login */
    const COOKIE_SOCIAL_CALLBACK = 'cookie_social_callback';
    const COOKIE_LOGIN = 'cookie_login';
    const COOKIE_EXPIRE = 43200;

    /**
     * LoginController constructor.
     * @param LineService $lineService
     */
    public function __construct(LineService $lineService)
    {
        $this->lineService = $lineService;
    }


    /**
     * ログイン.
     * @param Request $request {@link Request}
     */
    public function login(Request $request)
    {
        $this->validate(
            $request,
            ['email' => 'required',
                'password' => 'required',
                'referer' => 'required',],
            [],
            ['email' => 'メールアドレス',
                'password' => 'パスワード',]
        );

        $email = $request->input('email');
        $password = $request->input('password');
        $referer = $request->input('referer');

        // 認証実行
        if (Authenticate::attempt($email, $password)) {
            Cookie::queue(Cookie::forget(self::COOKIE_LOGIN));
            Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
            Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_DEFAULT), self::COOKIE_EXPIRE);
            // ドットマネーの場合、署名を行う
            if (\App\External\DotMoney::isDotMoneyUrl($referer)) {
                $user = Auth::user();
                $referer = \App\External\DotMoney::getSignedURL($referer, $user->name);
            }
            // login_source からのセッションの値を取得
            if (session()->has('login_source')) {
                $referer = session()->get('login_source');
                // セッションから login_source を削除
                session()->forget('login_source');
            }
            // ログインが成功した場合
            return redirect($referer);
        }

        return redirect()
            ->back()
            ->withInput(['referer' => $referer])
            ->with('message', 'ログインに失敗しました');
    }

    /**
     * ご意見箱.
     * @param Request $request {@link Request}
     */
    public function opinion(Request $request)
    {
        //
        $this->validate(
            $request,
            ['body' => ['required',],],
            [],
            ['body' => '本文',]
        );
        // 本文取得
        $body = $request->input('body');

        // メール送信を実行
        $options = ['user_id' => Auth()->user()->name, 'ip' => Device::getIp(),
            'ua' => $request->header('User-Agent'), 'created_at' => Carbon::now(), 'body' => $body];
        try {
            $mailable = new \App\Mail\Support('support', $options);
            \Mail::send($mailable);
        } catch (\Exception $e) {
        }

        return $this->redirectBack()->with('opinionSended', true);
    }

    /**
     * マイページ.
     */
    public function show()
    {
        $user = Auth::user();

        // 成果取得
        $aff_reward_list = $builder = $user->aff_rewards()
            ->ofWaiting()
            ->take(3)
            ->get();

        // お気に入り取得
        $user_program_list = $user->fav_programs()
            ->take(5)
            ->get();

        $recipe_id_list = $user->user_recipes()
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->pluck('recipe_id')
            ->all();
        $fav_recipe_data = Recipe::getRecipeListFromId($recipe_id_list);

        // New user count in the current month
        $carbon = Carbon::parse(Carbon::now());
        $day_10 = Carbon::parse(Carbon::now()->format('Y-m-10'));//今月10日
        $to_day = Carbon::parse(Carbon::now()->format('Y-m-d'));//本日

        if($to_day->gte($day_10)){//今日が10日を過ぎてるかチェック
            $startOfLastMonth = $carbon->startOfMonth();
        }else{
            $startOfLastMonth = $carbon->startOfMonth()->subMonth();
        }

        $endOfLastMonth = $startOfLastMonth->copy()->endOfMonth();
        $target_month = $startOfLastMonth->copy()->format("Ym");
        
        $newUserCount = User::where('friend_user_id', $user->id)
            ->whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])
            ->whereIn('status', [User::COLLEEE_STATUS, User::LOCK1_STATUS, User::LOCK2_STATUS])
            ->count() ?? 0;
        $referralBonus = UserReferralPointDetail::where('friend_user_id', $user->id)
            ->where('target_month', '=', $target_month)
            ->sum('referral_bonus') ?? 0;

        $friendReturnBonus = UserReferralPointDetail::where('friend_user_id', $user->id)
            ->where('target_month', '=', $target_month)
            ->sum('friend_return_bonus') ?? 0;
        
        return view('users.show', ['aff_reward_list' => $aff_reward_list, 'user_program_list' => $user_program_list,
            'fav_recipe_data' => $fav_recipe_data , 'newUserCount' => $newUserCount , 'referralBonus' => $referralBonus
            , 'friendReturnBonus' => $friendReturnBonus]);
    }

    /**
     * メールアドレス変更メール送信.
     * @param Request $request {@link Request}
     */
    public function editEmail(Request $request)
    {
        $user = Auth::user();

        //
        $this->validate(
            $request,
            [
                'email' => ['required', 'custom_email:1', 'custom_email_unblock', 'user_email_unique:'.$user->id],
            ],
            [
                'email.required' => 'メールアドレスを入力して下さい',
                'email.custom_email' => 'メールアドレスが不正な書式です',
                'email.custom_email_unblock' => '利用が不可能なメールアドレスです',
                'email.user_email_unique' => '利用が不可能なメールアドレスです',
            ],
            ['email' => 'メールアドレス',]
        );
        // メールアドレス取得
        $email = email_unquote($request->input('email'));

        // メールアドレスに変更がない場合
        if ($user->email == $email && $user->email_status == 0) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message', 'メールアドレスが変更されていません');
        }

        // 追加データ取得
        $data = (object)['user_id' => $user->id];

        // メールトークンID取得
        $email_token_id = EmailToken::createToken($email, EmailToken::EDIT_TYPE, $data);

        // メールトークン発行失敗
        if (!isset($email_token_id)) {
            return redirect()
                ->back()
                ->with('message', '登録作業に失敗しました。');
        }

        // メール送信を実行
        $options = ['email_token_id' => $email_token_id, 'user' => $user];
        try {
            $mailable = new \App\Mail\Colleee($email, 'confirm_email', $options);
            \Mail::send($mailable);
        } catch (\Exception $e) {
        }

        return view('users.edit_email_send', ['email' => $email, 'email_token_id' => $email_token_id]);
    }

    /**
     * メールトークン受け取り.
     * @param string $email_token_id メールトークンID
     */
    public function confirmEmail(string $email_token_id)
    {
        // ログアウトさせる
        if (Auth::check()) {
            Auth::logout();
        }

        // メールトークン取得
        $email_token = EmailToken::ofEnable(EmailToken::EDIT_TYPE, $email_token_id)->first();

        // メールトークンが存在しなかった場合
        if (!isset($email_token->id)) {
            return redirect(route('error'))
                ->with('back', ['url' => route('users.edit_email'),
                    'label' => '変更作業をやり直す',
                    'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                ->with('message', "トークンの有効期限が\n切れています。");
        }
        $data = json_decode($email_token->data);
        //
        $user = User::where('id', '=', $data->user_id)
            ->ofEnable()
            ->firstOrFail();

        // メールアドレスが重複する場合
        if (!User::checkUnique(['email' => $email_token->email], $user->id)) {
            return redirect(route('error'))
                ->with('back', ['url' => route('users.edit_email'),
                    'label' => '変更作業をやり直す',
                    'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                ->with('message', 'メールアドレスが重複するため変更作業を実行できません。');
        }

        // トークン作成に失敗した場合
        if (!Phone::create(Phone::EDIT_USER_EMAIL_SESSION_KEY, $user->tel)) {
            return redirect(route('error'))
                ->with('back', ['url' => route('users.edit_email'),
                    'label' => '変更作業をやり直す',
                    'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                ->with('message', '発信トークン発行作業に失敗しました。');
        }
        $ost_token = Phone::find(Phone::EDIT_USER_EMAIL_SESSION_KEY);

        return view('users.confirm_email', ['email_token' => $email_token, 'ost_token' => $ost_token]);
    }

    /**
     * メールアドレス保存.
     * @param Request $request {@link Request}
     */
    public function storeEmail(Request $request)
    {
        // ログアウトさせる
        if (Auth::check()) {
            Auth::logout();
        }

        //
        $this->validate(
            $request,
            ['email_token_id' => ['required',]],
            [],
            ['email_token_id' => 'メールトークン',]
        );
        // メールトークンID取得
        $email_token_id = $request->input('email_token_id');

        // メールトークン取得
        $email_token = EmailToken::ofEnable(EmailToken::EDIT_TYPE, $email_token_id)->first();

        // メールトークンが存在しなかった場合
        if (!isset($email_token->id)) {
            return redirect(route('error'))
                ->with('back', ['url' => route('users.edit_email'),
                    'label' => '変更作業をやり直す',
                    'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                ->with('message', "トークンの有効期限が\n切れています。");
        }
        $data = json_decode($email_token->data);

        //
        $user = User::where('id', '=', $data->user_id)
            ->ofEnable()
            ->firstOrFail();

        // 発信認証実行
        $res = Phone::attempt(Phone::EDIT_USER_EMAIL_SESSION_KEY);
        switch ($res) {
            case Phone::ERROR_STATUS:
                return redirect(route('error'))
                    ->with('back', ['url' => route('users.edit_email'),
                        'label' => '変更作業をやり直す',
                        'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                    ->with('message', '発信トークン発行作業に失敗しました。');
            case Phone::WAITING_STATUS:
                return redirect()
                    ->back()
                    ->with('message', "発信が確認できません。");
            default:
                break;
        }

        // 保存実行
        $res = $user->editEmail($email_token->email, Device::getIp(), $request->header('User-Agent'));
        // メールトークン削除
        $email_token->removeToken();

        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "変更作業に失敗しました");
        }

        // ログイン
        Auth::login($user, true);

        return view('users.store_email');
    }

    /**
     * 電話番号変更メール送信.
     * @param Request $request {@link Request}
     */
    public function editTel(Request $request)
    {
        $user = Auth::user();

        //
        $this->validate(
            $request,
            ['tel' => ['required', 'colleee_tel', 'confirmed', Rule::unique('users')],],
            ['tel.unique' => '既に同じ電話番号のユーザーが存在します',
                'tel.colleee_tel' => '有効な電話番号の書式ではありません',
                'tel.confirmed' => '電話番号が一致しません。再度ご確認下さい。'],
            ['tel' => '電話番号']
        );
        // メールアドレス取得
        $email = $user->email;

        // 追加データ取得
        $data = (object)['user_id' => $user->id, 'tel' => $request->input('tel')];

        // メールトークンID取得
        $email_token_id = EmailToken::createToken($email, EmailToken::EDIT_TEL_TYPE, $data);

        // メールトークン発行失敗
        if (!isset($email_token_id)) {
            return redirect()->back()
                ->with('message', '登録作業に失敗しました。');
        }

        // メール送信を実行
        $options = ['email_token_id' => $email_token_id, 'user' => $user];
        try {
            $mailable = new \App\Mail\Colleee($email, 'confirm_tel', $options);
            \Mail::send($mailable);
        } catch (\Exception $e) {
        }

        return view('users.edit_tel_send', ['email' => $email, 'email_token_id' => $email_token_id]);
    }

    /**
     * メールトークン受け取り.
     * @param Request $request {@link Request}
     * @param string $email_token_id メールトークンID
     */
    public function confirmTel(Request $request, string $email_token_id)
    {
        // ログアウトさせる
        if (Auth::check()) {
            Auth::logout();
        }

        // メールトークン取得
        $email_token = EmailToken::ofEnable(EmailToken::EDIT_TEL_TYPE, $email_token_id)->first();

        // メールトークンが存在しなかった場合
        if (!isset($email_token->id)) {
            return redirect(route('error'))
                ->with('back', ['url' => route('users.edit_tel'),
                    'label' => '変更作業をやり直す',
                    'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                ->with('message', "トークンの有効期限が\n切れています。");
        }
        $data = json_decode($email_token->data);
        $tel = $data->tel;

        // 電話番号が重複する場合
        if (!User::checkPhoneUnique($tel, $data->user_id)) {
            return redirect(route('error'))
                ->with('back', ['url' => route('users.edit_tel'),
                    'label' => '変更作業をやり直す',
                    'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                ->with('message', '電話番号が重複するため変更作業を実行できません。');
        }

        // トークン作成に失敗した場合
        if (!Phone::create(Phone::EDIT_USER_TEL_SESSION_KEY, $tel)) {
            return redirect(route('error'))
                ->with('back', ['url' => route('users.edit_tel'),
                    'label' => '変更作業をやり直す',
                    'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                ->with('message', '発信トークン発行作業に失敗しました。');
        }
        $ost_token = Phone::find(Phone::EDIT_USER_TEL_SESSION_KEY);
        return view('users.confirm_tel', ['email_token' => $email_token, 'ost_token' => $ost_token]);
    }

    /**
     * 電話番号保存.
     * @param Request $request {@link Request}
     */
    public function storeTel(Request $request)
    {
        // ログアウトさせる
        if (Auth::check()) {
            Auth::logout();
        }

        //
        $this->validate(
            $request,
            ['email_token_id' => ['required',]],
            [],
            ['email_token_id' => 'メールトークン',]
        );
        // メールトークンID取得
        $email_token_id = $request->input('email_token_id');

        // メールトークン取得
        $email_token = EmailToken::ofEnable(EmailToken::EDIT_TEL_TYPE, $email_token_id)->first();

        // メールトークンが存在しなかった場合
        if (!isset($email_token->id)) {
            return redirect(route('error'))
                ->with('back', ['url' => route('users.edit_tel'),
                    'label' => '変更作業をやり直す',
                    'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                ->with('message', "トークンの有効期限が\n切れています。");
        }
        $data = json_decode($email_token->data);
        //
        $user = User::where('id', '=', $data->user_id)
            ->ofEnable()
            ->firstOrFail();

        // 発信認証実行
        $res = Phone::attempt(Phone::EDIT_USER_TEL_SESSION_KEY);
        switch ($res) {
            case Phone::ERROR_STATUS:
                return redirect(route('error'))
                    ->with('back', ['url' => route('users.edit_tel'),
                        'label' => '変更作業をやり直す',
                        'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"])
                    ->with('message', '発信トークン発行作業に失敗しました。');
            case Phone::WAITING_STATUS:
                return redirect()
                    ->back()
                    ->with('message', "発信が確認できません。");
            default:
                break;
        }

        // 保存実行
        $res = $user->editTel($data->tel, Device::getIp(), $request->header('User-Agent'));
        // メールトークン削除
        $email_token->removeToken();

        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "変更作業に失敗しました");
        }

        // ログイン
        Auth::login($user, true);
        return view('users.store', ['item' => '電話番号']);
    }

    /**
     * パスワード保存.
     * @param Request $request {@link Request}
     */
    public function storePassword(Request $request)
    {
        //
        $this->validate(
            $request,
            [
                'cur_password' => ['required'],
                'password' => ['required', 'colleee_password', 'confirmed',],
            ],
            [
                'password.colleee_password' => 'パスワードに使用できない文字が入力されています。再度入力して下さい',
            ],
            [
                'cur_password' => '現在のパスワード',
                'password' => '新しいパスワード',
            ]
        );

        //
        $user = Auth::user();
        // 認証確認
        if (!Authenticate::checkPassward($user, $request->input('cur_password'))) {
            return redirect()->back()
                ->with('message', "パスワードが一致しません");
        }

        // パスワードの更新
        $res = $user->editPassword($request->input('password'), Device::getIp(), $request->header('User-Agent'));

        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "変更作業に失敗しました");
        }

        return view('users.store', ['item' => 'パスワード']);
    }

    /**
     * ニックネーム保存.
     * @param Request $request {@link Request}
     */
    public function storeNickname(Request $request)
    {
        //
        $user = Auth::user();
        $nickName = $request->input('nickname') ?? null;

        if ($nickName && $user->nickname && $user->nickname == $nickName) {
            $min = '';
        } else {
            $min = 'min:2';
        }

        $this->validate(
            $request,
            ['nickname' => ['required', 'max:10', $min],],
            [],
            ['nickname' => 'ニックネーム',]
        );

        $user->nickname = $nickName;
        // 保存実行
        $res = DB::transaction(function () use ($user) {
            // 登録実行
            $user->save();
            return true;
        });

        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "変更作業に失敗しました");
        }

        return view('users.store', ['item' => 'ニックネーム']);
    }

    /**
     * 都道府県保存.
     * @param Request $request {@link Request}
     */
    public function storePrefecture(Request $request)
    {
        //
        $this->validate(
            $request,
            ['prefecture_id' => ['required', 'integer', 'between:1,47',],],
            [],
            ['prefecture_id' => '都道府県',]
        );

        //
        $user = Auth::user();
        $user->prefecture_id = $request->input('prefecture_id');
        // 保存実行
        $res = DB::transaction(function () use ($user) {
            // 登録実行
            $user->save();
            return true;
        });

        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "変更作業に失敗しました");
        }

        return view('users.store', ['item' => 'お住まいの都道府県']);
    }

    /**
     * メール設定保存.
     * @param Request $request {@link Request}
     */
    public function storeEmailSetting(Request $request)
    {
        //
        $this->validate(
            $request,
            [
                'email_magazine' => ['required', 'integer', 'in:0,1'],
            ],
            [],
            [
                'email_magazine' => 'メールマガジンの受信',
            ]
        );
        //
        $user = Auth::user();
        $user->email_magazine = $request->input('email_magazine');
        // 保存実行
        $res = DB::transaction(function () use ($user) {
            // 登録実行
            $user->save();
            return true;
        });

        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "変更作業に失敗しました");
        }

        return view('users.store_email_setting');
    }


    /**
     * メール設定保存.
     * @param Request $request {@link Request}
     */
    public function newPassword(Request $request)
    {
        //
        $this->validate(
            $request,
            [
                'password' => ['required', 'colleee_password', 'confirmed',],
            ],
            [
                'password.colleee_password' => 'パスワードに使用できない文字が入力されています。再度入力して下さい',
            ],
            [
                'password' => '新しいパスワード',
            ]
        );

        //
        $user = Auth::user();

        // パスワードの更新
        $res = $user->editPassword($request->input('password'), Device::getIp(), $request->header('User-Agent'));

        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "変更作業に失敗しました");
        }
        $user = Auth::user();
        //clear google
        if($request->input('type') == '1') {
            $user->google_id = '';
        }elseif($request->input('type') == '0') {
         //clear line
            $user->line_id = '';
        }

        $res = DB::transaction(function () use ($user) {
            // 登録実行
            $user->save();
        });

        return view('users.store_new_pwd');
    }

    /**
     * ページャ取得.
     * @param mixed $builder
     * @param int $page ページ番号
     * @return LengthAwarePaginator ページャ
     */
    private function getPaginator($builder, int $page)
    {
        // 総件数取得
        $total = $builder->count();

        // 件数
        $limit = Device::getDeviceId() == 1 ? self::PC_LIMIT : self::SP_LIMIT;

        // ページ数
        $page = min(max($page, 1), ceil($total / $limit));

        // お気に入りプログラムリスト取得
        $program_list = $builder->take($limit)
            ->skip(($page - 1) * $limit)
            ->get();
        // ページネーション作成
        return new LengthAwarePaginator($program_list, $total, $limit, $page);
    }

    /**
     * お気に入り一覧.
     * @param int $page ページ番号
     */
    public function programList(int $page = 1)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        $builder = $user->fav_programs();

        // ページネーション作成
        $paginator = $this->getPaginator($builder, $page);

        return view('users.program_list', ['paginator' => $paginator]);
    }

    /**
     * お気に入り登録.
     * @param Program $program プログラム
     */
    public function addProgram(Program $program)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        // お気に入り登録実行
        UserProgram::addProgram($user->id, $program->id);

        return redirect()->back();
    }

    /**
     * お気に入り解除.
     * @param Program $program プログラム
     */
    public function removeProgram(Program $program)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        // お気に入り削除実行
        UserProgram::removeProgram($user->id, $program->id);

        return redirect()->back();
    }

    /**
     * お気に入りレシピ一覧.
     * @param int $page ページ番号
     */
    public function recipeList(int $page = 1)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        $builder = $user->user_recipes();

        // 総件数取得
        $total = $builder->count();

        // 件数
        $limit = Device::getDeviceId() == 1 ? self::PC_LIMIT : self::SP_LIMIT;

        // ページ数
        $page = min(max($page, 1), ceil($total / $limit));

        // お気に入りレシピIDリスト取得
        $recipe_id_list = $builder->take($limit)
            ->orderBy('created_at', 'desc')
            ->skip(($page - 1) * $limit)
            ->pluck('recipe_id')
            ->all();

        $fav_recipe_data = Recipe::getRecipeListFromId($recipe_id_list);

        $recipe_list = collect();
        if (isset($fav_recipe_data) && $fav_recipe_data->result->status && !empty($fav_recipe_data->items)) {
            $recipe_list = collect($fav_recipe_data->items);
        }

        // ページネーション作成
        $paginator = new LengthAwarePaginator($recipe_list, $total, $limit, $page);

        return view('users.recipe_list', ['paginator' => $paginator]);
    }

    /**
     * お気に入りレシピ登録.
     * @param int $recipe_id レシピID
     */
    public function addRecipe(int $recipe_id)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        // お気に入り登録実行
        UserRecipe::addRecipe($user->id, $recipe_id);

        return redirect()->back();
    }

    /**
     * お気に入り解除.
     * @param int $recipe_id レシピID
     */
    public function removeRecipe(int $recipe_id)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        // お気に入り削除実行
        UserRecipe::removeRecipe($user->id, $recipe_id);

        return redirect()->back();
    }

    /**
     * 獲得予定成果一覧.
     * @param int $page ページ番号
     */
    public function rewardList(int $page = 1)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        $builder = $user->aff_rewards()
            ->ofWaiting();

        // ページネーション作成
        $paginator = $this->getPaginator($builder, $page);

        return view('users.aff_reward_list', ['paginator' => $paginator]);
    }

    /**
     * 獲得ポイント一覧.
     * @param int $type 種類
     * @param int $page ページ番号
     */
    public function pointList(int $type = 1, int $page = 1)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        $builder = $user->points()->ofReward($type);

        // ページネーション作成
        $paginator = $this->getPaginator($builder, $page);

        $set_date   = date('Y-m-d H:i:s');

        $review_point_management = ReviewPointManagement::where('start_at', '<=', $set_date)->where(function ($query) use ($set_date) {
            // stop_atがnullの場合（終了日が設定されていない）もしくは終了日の範囲内
            $query->whereNull('stop_at')
                ->orWhere('stop_at', '>=', $set_date);
        })->first();

        return view('users.point_list', ['type' => $type, 'paginator' => $paginator, 'review_point_management' => $review_point_management]);
    }

    /**
     * 交換申し込み一覧.
     * @param int $page ページ番号
     */
    public function exchangeList(int $page = 1)
    {
        // ユーザー情報を取得
        $user = Auth::user();

        $builder = $user->exchange_requests();

        // ページネーション作成
        $paginator = $this->getPaginator($builder, $page);

        return view('users.exchange_list', ['paginator' => $paginator]);
    }

    /**
     * ブログ保存.
     * @param Request $request {@link Request}
     */
    public function storeBlog(Request $request)
    {
        $user = Auth::user();
        // ブログが登録済みの場合
        if ($user->blog == 1) {
            return redirect(route('friends.index'));
        }
        //
        $this->validate(
            $request,
            ['url' => ['required', 'url'],],
            [],
            ['url' => 'ブログURL',]
        );

        // ユーザー情報を取得
        $user->blog = 1;

        $user_point = UserPoint::getDefault(
            $user->id,
            UserPoint::ADMIN_TYPE,
            0,
            50,
            'ブログ申請'
        );

        // トランザクション処理
        $res = $user_point->addPoint(function () use ($user) {
            // 保存実行
            $user->save();
            return true;
        });
        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "登録作業に失敗しました");
        }

        return view('users.store_blog');
    }

    /**
     * 誕生日ポイント配布
     */
    public function birthday()
    {
        $user = Auth::user();
        $user_id = $user->id;

        $now = Carbon::now();
        $birthday = $user->birthday->startOfDay();
        $diff = $now->diffInDays($birthday);
        $diff = abs((int)$diff);
        $birthday = $birthday->copy()->addYears($diff);

        $start_at = $now->copy()->addDays(-60)->startOfDay();
        $end_at = $now->copy()->addDays(1)->endOfDay();

        $parent_id = $birthday->year;

        // 未配布の場合
        if (!UserPoint::where('user_id', '=', $user_id)
            ->where('type', '=', UserPoint::BIRTYDAY_BONUS_TYPE)
            ->where('parent_id', '=', $parent_id)
            ->exists() && $start_at->lte($birthday) && $birthday->lte($end_at)) {
            // ランクを取得
            $user_rank = $user->ranks()
                ->ofTerm($now)
                ->first();
            $user_rank_value = $user_rank->rank ?? 0;
            $bonus = config('bonus.birthday')[$user_rank_value];

            $user_point = UserPoint::getDefault(
                $user_id,
                UserPoint::BIRTYDAY_BONUS_TYPE,
                0,
                $bonus,
                '誕生日ポイント'
            );
            $user_point->parent_id = $parent_id;

            // トランザクション処理
            $user_point->addPoint(null, function () use ($user_id, $parent_id) {
                return !UserPoint::where('user_id', '=', $user_id)
                    ->where('type', '=', UserPoint::BIRTYDAY_BONUS_TYPE)
                    ->where('parent_id', '=', $parent_id)
                    ->exists();
            });
        }

        return redirect(route('users.point_list', ['type' => UserPoint::OTHER_GROUP_TYPE]));
    }

    /**
     * Handle result which Line API returned.
     * @param Request $request
     * @return void
     */
    public function handleLineCallback(Request $request) {
        $lineResponse = $request->all();
        $code = $lineResponse['code'] ?? '';
        if (empty($code)) {
            return  redirect('/login')->with('message', 'ログインに失敗しました');
        }

        abort_if(!empty($lineResponse['error']), redirect('/login')->with('message', 'ログインに失敗しました'));

        // Get token
        $tokenResponse = $this->lineService->getLineToken($code);
        abort_if(!$tokenResponse, redirect('/login')->with('message', 'ログインに失敗しました'));

        //Get user info
        $profileResponse = $this->lineService->getUserProfile($tokenResponse['access_token']);
        abort_if(!$profileResponse, redirect('/login')->with('message', 'ログインに失敗しました'));

        $profile = $this->lineService->verifyIDToken($tokenResponse['id_token']);


        //check connection with line
        $user = Auth::user();
        $line_id = $profileResponse['userId'];
        $line_token = $tokenResponse['access_token'];
        $line_mail = ($profile['email']) ?? '';
        
        Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
        Cookie::queue(self::COOKIE_SOCIAL_CALLBACK, Crypt::encryptString(self::LOGIN_LINE), self::COOKIE_EXPIRE);

        if (!empty($user)) {
            $this->connectiveWithLine($line_id, $line_token, $user);
            if (empty($user->line_id)) {
                $users = User::where('line_id', '=', $line_id)->where('deleted_at')->ofEnable()->first();
                if (!empty($users)) {
                    Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
                    Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_LINE), self::COOKIE_EXPIRE);
                    Auth::login($users);
                    return redirect('/users/edit_line');
                }
                return redirect('/users/edit_line');
            }

            // 詳細ページにリダイレクト
            if (session()->has('login_source')) {
                $referer = session()->get('login_source');
                // セッションから login_source を削除
                session()->forget('login_source');

                return redirect($referer);
            }

            return redirect('/');
        } else {
            $entry_user['line_id'] = $line_id;
            $user = User::where('line_id', '=', $line_id)->where('deleted_at')->ofEnable()->first();
            $line_account = LineAccount::where('line_id', '=', $line_id)->first();
            if (!empty($user) && !empty($line_account)) {
                Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
                Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_LINE), self::COOKIE_EXPIRE);
                Auth::login($user);

                // 詳細ページにリダイレクト
                if (session()->has('login_source')) {
                    $referer = session()->get('login_source');
                    // セッションから login_source を削除
                    session()->forget('login_source');

                    return redirect($referer);
                }

                return redirect('/');
            } else if (!empty($line_mail)) {
                $user = User::where('email', '=', $line_mail)->whereNull('deleted_at')->ofEnable()->first();
                if (!empty($user)) {
                    $this->connectiveWithLine($line_id, $line_token, $user);
                    Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
                    Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_LINE), self::COOKIE_EXPIRE);
                    Auth::login($user);
                    return redirect('/');
                } else {
                    Session::forget('message');
                    $entry_user['email'] = $line_mail;
                    $entry_user['line_token'] = $line_token;
                    session()->put(self::ENTRY_USER_SESSION_LINE_ACESS_TOKEN, $line_token);
                    return view('entries.create', ['entry_user' => $entry_user]);
                }
            }

            Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
            Session::forget('message');
            return redirect('/entries')->with('error_line', 'true');
        }
    }

    public function connectiveWithLine($line_id, $line_token, $user) {
        $user->line_id = $line_id;
        $res = DB::transaction(function () use ($user, $line_id, $line_token) {
            $user_id = $user->id;
            // 登録実行
            $user->save();
            // Lineテーブルにも保存
            $line = LineAccount::firstOrNew(['user_id' => $user_id]);
            $line->line_id = $line_id;
            $line->token = $line_token;
            $line->save();
        });
        return $res;
    }

    public function cancelLine() {
        $user = Auth::user();
        if (!empty($user)) {
            $user->line_id = '';
            $res = DB::transaction(function () use ($user) {
                $user_id = $user->id;
                LineAccount::where('user_id', $user_id)->delete();
                // 登録実行
                $user->save();
            });
        }
        return redirect('/users/edit_line');
    }

/////google

    public function createRegistGoogle()
    {
        try {
            return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
        } catch (\Exception $exception) {
            return back()->withErrors(['error_login_gg' => '失敗しました、再試行してください。']);
        }
    }
    public function callbackGoogle(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            session()->put('google_user', $googleUser);
        } catch (\Exception $exception) {
            $googleUser = session()->get('google_user');
        }
        if (empty($googleUser)) {
            return  redirect('/login/0')->withErrors(['error_login_gg' => '失敗しました、再試行してください。']);
        }
        $google_token = $googleUser->token;
        $ggResponse = $request->all();
        $code = $ggResponse['code'] ?? '';

        if (empty($code) || !empty($ggResponse['error']) || !$google_token || !$googleUser['email'] ) {
            return  redirect('/login/0')->withErrors(['error_login_gg' => '失敗しました、再試行してください。']);
        }

        //check connection with google
        $user = Auth::user();
        $google_id = $googleUser['id'];
        $google_mail = ($googleUser['email']) ?? '';
        
        Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
        Cookie::queue(self::COOKIE_SOCIAL_CALLBACK, Crypt::encryptString(self::LOGIN_GOOGLE), self::COOKIE_EXPIRE);

        //have user logged
        if (!empty($user)) {
            if($user->email == $google_mail){
                $user->google_id = $google_id;
                DB::transaction(function () use ($user) {
                    $user->save();
                });
                if (empty($user->google_id)) {
                    $users = User::where('google_id', '=', $google_id)->where('deleted_at')->ofEnable()->first();
                    if (!empty($users)) {
                        Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
                        Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_GOOGLE), self::COOKIE_EXPIRE);
                        Auth::login($users);
                        return redirect('/users/edit_google');
                    }
                    return redirect('/users/edit_google');
                }

                // 詳細ページにリダイレクト
                if (session()->has('login_source')) {
                    $referer = session()->get('login_source');
                    // セッションから login_source を削除
                    session()->forget('login_source');

                    return redirect($referer);
                }

                return redirect('/');
            } else {
                return redirect('/users/edit_google')->with('message_error_email', 'リンクの失敗：メールアドレスが一致しません');
            }


        } else {
            $entry_user['google_id'] = $google_id;
            $user = User::where('google_id', '=', $google_id)->where('deleted_at')->ofEnable()->first();
            if (!empty($user)) {
                Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
                Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_GOOGLE), self::COOKIE_EXPIRE);
                Auth::login($user);

                // 詳細ページにリダイレクト
                if (session()->has('login_source')) {
                    $referer = session()->get('login_source');
                    // セッションから login_source を削除
                    session()->forget('login_source');

                    return redirect($referer);
                }

                return redirect('/');
            } else if (!empty($google_mail)) {
                $user = User::where('email', '=', $google_mail)->whereNull('deleted_at')->ofEnable()->first();
                if (!empty($user)) {
                    $user->google_id = $google_id;
                    DB::transaction(function () use ($user) {
                        $user->save();
                    });
                    Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
                    Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_GOOGLE), self::COOKIE_EXPIRE);
                    Auth::login($user);
                    return redirect('/');
                } else {
                    Session::forget('message');
                    $entry_user['email'] = $google_mail;
                    session()->put(self::ENTRY_USER_SESSION_GOOGLE_ACESS_TOKEN, $google_token);
                    return view('entries.create', ['entry_user' => $entry_user]);
                }
            }
            
            Session::forget('message');
            Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
            return redirect('/entries')->with('error_google', 'true');
        }

    }
    public function cancelGoogle() {
        $user = Auth::user();
        if (!empty($user)) {
            $user->google_id = '';
            $res = DB::transaction(function () use ($user) {
                // 登録実行
                $user->save();
            });
        }
        return redirect('/users/edit_google');
    }


}
