<?php
namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

use \App\Device\Device;
use App\User;
use App\UserLogin;

/**
 * Description of CustomEloquentUserProvider
 *
 * @author t_moriizumi
 */
class CustomEloquentUserProvider extends EloquentUserProvider
{
    /**
     * ログイン履歴追加.
     * @param User $user ユーザー情報
     */
    private function addLogin(User $user)
    {
        $ip = Device::getIp();
        $ua = request()->header('User-Agent');
        // ログイン情報登録
        UserLogin::addLogin($ip, $ua, $user);
    }

    /**
     * ユーザー検証.
     * @param User $user ユーザー情報
     * @return User|null ユーザー情報
     */
    private function checkUser(?User $user)
    {
        // ユーザーが存在しない、またはログイン可能ユーザーではない場合
        if (!isset($user->id) || !in_array($user->status, [
            User::COLLEEE_STATUS, User::LOCK1_STATUS, User::LOCK2_STATUS], true)) {
            return null;
        }

        // ログイン情報登録
        $this->addLogin($user);
        return $user;
    }

    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        $user = parent::retrieveById($identifier);
        return $this->checkUser($user);
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        $user = parent::retrieveByToken($identifier, $token);
        return $this->checkUser($user);
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(UserContract $user, $token)
    {
        parent::updateRememberToken($user, $token);
        // ログイン情報登録
        $this->addLogin($user);
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = parent::retrieveByCredentials($credentials);
        return $this->checkUser($user);
    }
}
