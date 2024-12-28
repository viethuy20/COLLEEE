<?php
namespace App\Http\Middleware;

use Auth;
use Closure;
use \Cookie;

use App\OstToken;

class Phone
{
    const AUTH_ROLE = 'auth';
    const NON_AUTH_ROLE = 'non_auth';
    const COOKIE_KEY = 'phone_token';
    const COOKIE_EXPIRE_MIN = 24 * 60;
    /** 成功状態. */
    const SUCCESS_STATUS = 0;
    /** 失敗状態. */
    const ERROR_STATUS = 1;
    /** 待ち状態. */
    const WAITING_STATUS = 2;

    /** セッションキー */
    const AUTH_SESSION_KEY = 'auth_phone';
    const CREATE_USER_SESSION_KEY = 'create_user';
    const EDIT_USER_EMAIL_SESSION_KEY = 'edit_user_email';
    const EDIT_USER_TEL_SESSION_KEY = 'edit_user_tel';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string $role 権限
     * @return mixed
     */
    public function handle($request, Closure $next, string $role)
    {
        if ($role == self::AUTH_ROLE && !self::authenticate()) {
            $referer = $request->isMethod('post') ? url()->previous() : url()->current();
            return redirect(route('phones.index'). '?' . http_build_query(['referer' => $referer]));
        }
        if ($role == self::NON_AUTH_ROLE && self::authenticate()) {
            $referer = $request->input('referer') ?? route('website.index');
            return redirect($referer);
        }
        return $next($request);
    }

    /**
     * トークン発行.
     * @param string $session_key セッションキー
     * @param string $tel 電話番号
     * @return bool 発行に成功したらtrueを、失敗したらfalseを返す
     */
    public static function create(string $session_key, string $tel) : bool
    {
        // トークンID取得
        $ost_token_id = session()->get($session_key);
        // トークンを発行
        $cur_ost_token = isset($ost_token_id) ? OstToken::find($ost_token_id) : null;
        $ost_token = (isset($cur_ost_token) && $cur_ost_token->tel == $tel) ?
            $cur_ost_token : OstToken::createToken($tel);
        // トークンを確認
        if (!isset($ost_token->id)) {
            return false;
        }
        // トークンID登録
        session()->put($session_key, $ost_token->id);
        return true;
    }

    /**
     * トークン情報取得.
     * @param string $session_key セッションキー
     * @return OstToken|null トークン情報
     */
    public static function find(string $session_key) : ?OstToken
    {
        // トークンID取得
        $ost_token_id = session()->get($session_key);
        // トークンID取得に失敗した場合
        if (!isset($ost_token_id)) {
            return null;
        }
        return OstToken::find($ost_token_id);
    }

    /**
     * トークン情報削除.
     * @param string $session_key セッションキー
     */
    public static function clean(string $session_key)
    {
        // セッション破棄
        session()->forget($session_key);
    }

    /**
     * 認証実行.
     * @param string $session_key セッションキー
     * @return int 状態
     */
    public static function attempt(string $session_key) : int
    {
        $ost_token = self::find($session_key);
        // トークン取得に失敗した場合
        if (!isset($ost_token->id)) {
            // セッション破棄
            self::clean($session_key);
            return self::ERROR_STATUS;
        }
        // 確認
        $status = $ost_token->checkStatus();
        // 認証が完了していなかった場合
        if ($status == OstToken::CREATE_CHECK_STATUS) {
            return self::WAITING_STATUS;
        }
        // 認証が失敗した場合
        if ($status == OstToken::ERROR_CHECK_STATUS) {
            // セッション破棄
            self::clean($session_key);
            return self::ERROR_STATUS;
        }
        // クッキーにトークンを保存
        Cookie::queue(Cookie::make(
            self::COOKIE_KEY,
            $ost_token->id,
            self::COOKIE_EXPIRE_MIN,
            null,
            null,
            is_secure(),
            true,
            false,
            'lax'
        ));
        // セッション破棄
        self::clean($session_key);
        // 成功
        return self::SUCCESS_STATUS;
    }

    /**
     * 認証確認.
     * @param string|NULL $tel 電話番号
     * @return bool 認証していた場合はtrueを、認証していない場合はfalseを返す
     */
    public static function authenticate($tel = null) : bool
    {
        // クッキーからトークンを確認
        $ost_token_id = Cookie::get(self::COOKIE_KEY, null);
        if (!isset($ost_token_id)) {
            return false;
        }
        $p_tel = null;
        if (isset($tel)) {
            $p_tel = $tel;
        } else {
            $user = Auth::user();
            if (!isset($user->id)) {
                return false;
            }
            $p_tel = $user->tel;
        }
        return OstToken::ofEnable($ost_token_id)
            ->where('tel', '=', $p_tel)
            ->exists();
    }
}
