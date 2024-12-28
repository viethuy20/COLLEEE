<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

use App\User;

use App\Exceptions\LockException;

class Lock
{
    const ALL_ROLE = 'all';
    const EXCHANGE_ROLE = 'exchange';

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
        self::authenticate($role);
        return $next($request);
    }

    /**
     * 認証確認.
     * @param string $role 権限
     */
    public static function authenticate(string $role) {
        $user = Auth::user();
        // 有効なユーザーではない場合
        if (!isset($user->id)) {
            abort(403, 'Forbidden');
        }

        // ユーザーのロックの種類を確認
        $type = ($user->status == User::LOCK1_STATUS) ? LockException::LOCK1_TYPE : LockException::LOCK2_TYPE;

        // 全ロックの場合
        if ($role == self::ALL_ROLE && !in_array($user->status, [User::COLLEEE_STATUS, User::LOCK1_STATUS], true)) {
            throw new LockException($type);
        }
        // 交換ロックの場合
        if ($role == self::EXCHANGE_ROLE && $user->status != User::COLLEEE_STATUS) {
            throw new LockException($type);
        }
    }
}
