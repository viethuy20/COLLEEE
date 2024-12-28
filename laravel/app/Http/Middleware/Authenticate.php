<?php

namespace App\Http\Middleware;

use Auth;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

use App\User;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            $referer = $request->isMethod('post') ?  url()->previous() : url()->current();
            return route('entries.regist') . '?' . http_build_query(['referer' => $referer]);
        }
    }

    /**
     * パスワード認証できているか確認する.
     * @param User $user ユーザー情報
     * @param string $password パスワード
     * @return bool 認証できた場合はtrueを、認証できなかった場合はfalseを返す
     */
    public static function checkPassward(User $user, string $password) : bool
    {
        // ユーザー情報の取得、またはパスワード認証に失敗した場合
        if (!isset($user->id) || !app()->make('hash')->check($password, $user->getAuthPassword())) {
            return false;
        }
        return true;
    }

    /**
     * 認証できたユーザーを返す.
     * @param string $email メールアドレス
     * @param string $password パスワード
     * @return User|null 認証できた場合はユーザー情報を、認証できなかった場合はnullを返す
     */
    public static function getCheckedUser(string $email, string $password) : ?User
    {
        // ユーザー情報を取得
        $user = User::where('email', '=', $email)
            ->ofEnable()
            ->first();
        // ユーザー情報の取得、またはパスワード認証に失敗した場合
        if (!isset($user->id) || !self::checkPassward($user, $password)) {
            return null;
        }
        return $user;
    }

    /**
     * 認証実行.
     * @param string $email メールアドレス
     * @param string $password パスワード
     * @return bool 認証に成功したらtrueを、失敗したらfalseを返す
     */
    public static function attempt(string $email, string $password) : bool
    {
        // ログインしていた場合はログアウトする
        if (Auth::check()) {
            Auth::logout();
        }
        // ユーザー情報を取得
        $user = self::getCheckedUser($email, $password);
        // ユーザー情報の取得に失敗した場合
        if (!isset($user->id)) {
            return false;
        }
        // 認証状態に変更
        Auth::login($user, true);
        return true;
    }

    /**
     * 認証できたユーザーを返す.
     * @param string $email メールアドレス
     * @param string $line_id
     * @return User|null
     */
    public static function getCheckedUserWithLine(string $email, string $line_id) : ?User
    {
        // ユーザー情報を取得
        $user = User::where('email', '=', $email)
            ->ofEnable()
            ->first();
        // ユーザー情報の取得、またはパスワード認証に失敗した場合
        if (!isset($user->id) || $line_id != $user->line_id) {
            return null;
        }
        return $user;
    }

    /**
     * 認証できたユーザーを返す.
     * @param string $email メールアドレス
     * @param string $google_id
     * @return User|null
     */
    public static function getCheckedUserWithGoogle(string $email, string $google_id) : ?User
    {
        // ユーザー情報を取得
        $user = User::where('email', '=', $email)
            ->ofEnable()
            ->first();
        // ユーザー情報の取得、またはパスワード認証に失敗した場合
        if (!isset($user->id) || $google_id != $user->google_id) {
            return null;
        }
        return $user;
    }
}
