<?php
namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Mail;

use App\Http\Middleware\Authenticate;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;

class WithdrawalsController extends Controller
{
    /**
     * 退会登録セッションキー.
     */
    const WITHDRAWAL_SESSION_KEY = 'withdrawal_key';
    const LOGIN_DEFAULT = 0;
    const LOGIN_LINE = 1;
    const LOGIN_GOOGLE = 2;
    const COOKIE_LOGIN = 'cookie_login';

    /**
     * 退会確認.
     * @param Request $request {@link Request}
     */
    public function confirm(Request $request)
    {
        $thisUser = Auth::user();
        if (!Cookie::has(self::COOKIE_LOGIN)) {
            Auth::logout();
            return back();
        }

        $cookie = Crypt::decryptString(Cookie::get(self::COOKIE_LOGIN)) ?? null;

        if ($cookie === null) {
            Auth::logout();
            return back();
        }

        if ($cookie != self::LOGIN_LINE && $cookie != self::LOGIN_GOOGLE) {
            $role = [
                'email' => ['required', 'custom_email:1',],
                'password' => ['required',],
            ];
            $msg = [
                'email.required' => 'メールアドレスを入力して下さい',
                'email.custom_email' => 'メールアドレスが不正な書式です',
                'password.required' => 'パスワードを入力してください',
            ];
            $label = [
                'email' => 'メールアドレス',
                'password' => 'パスワード',
                'reasons' => '退会の理由',
                'free_reason' => 'その他のご意見',
            ];
        } else {
            $role = [
                'email' => ['required', 'custom_email:1',]
            ];
            $msg = [
                'email.required' => 'メールアドレスを入力して下さい',
                'email.custom_email' => 'メールアドレスが不正な書式です',
            ];
            $label = [
                'email' => 'メールアドレス',
                'reasons' => '退会の理由',
                'free_reason' => 'その他のご意見',
            ];
        }
        //
        $this->validate(
            $request ,$role ,$msg, $label
        );
        // メールアドレス
        $email = $request->input('email');
        // パスワード
        $password = $request->input('password');
        // ライン ID を取得する
        $line_id = $request->input('line_id') ?? null;
        // Google ID を取得する
        $google_id = $request->input('google_id') ?? null;
        // 退会理由（複数選択）
        $withdrawal['reasons'] = empty($request->input('reasons')) ? [] : $request->input('reasons');
        // 退会理由（自由入力）
        $withdrawal['free_reason'] = $request->input('free_reason');

        // 入力メールアドレスまたはパスワードが登録済ユーザー情報と一致しない場合
        switch (true) {
            case $cookie == self::LOGIN_LINE && $line_id:
                $user = Authenticate::getCheckedUserWithLine($email, $line_id);
                $msgError = '入力されたメールアドレスが正しくありません';
                break;

            case $cookie == self::LOGIN_GOOGLE && $google_id:
                $user = Authenticate::getCheckedUserWithGoogle($email, $google_id);
                $msgError = '入力されたメールアドレスが正しくありません';
                break;
            
            default:
                $user = Authenticate::getCheckedUser($email, $password);
                $msgError = '入力されたメールアドレスまたはパスワードが正しくありません';
                break;
        }
       
        $authed_user = $thisUser;
        if (!isset($user->id) || $user->id != $authed_user->id) {
            return redirect()
                ->back()
                ->withInput()
                ->with('message', $msgError);
        }

        // セッションに保存
        session()->put(self::WITHDRAWAL_SESSION_KEY, $withdrawal);

        return view('withdrawals.confirm', ['withdrawal' => $withdrawal]);
    }

    /**
     * 退会登録.
     */
    public function store()
    {
        // セッションを確認
        if (!session()->has(self::WITHDRAWAL_SESSION_KEY)) {
            abort(404, 'Not Found.');
        }

        // セッションから値を取得
        $withdrawal = session()->get(self::WITHDRAWAL_SESSION_KEY);

        // 退会ステータスに更新
        $user = Auth::user();
        // 自主退会実行
        $res = $user->selfWithdraw();

        //
        if (!$res) {
            return redirect()
                ->back()
                ->with('message', "変更作業に失敗しました");
        }

        // メール送信を実行
        $email = $user->email;
        try {
            $mailable = new \App\Mail\Colleee($email, 'withdrawal_complite');
            Mail::send($mailable);
        } catch (\Exception $e) {
        }

        // 運用者メール送信用退会理由の作成
        $reasons_map = config('map.withdrawal_reasons');
        $withdrawal_reasons = [];
        foreach ($withdrawal['reasons'] as $value) {
            $withdrawal_reasons[] = $reasons_map[$value];
        }
        $withdrawal_reasons[] = $withdrawal['free_reason'];

        // セッションから値を削除
        session()->forget(self::WITHDRAWAL_SESSION_KEY);

        // 正常に退会処理が完了したらログアウト
        if (Auth::check()) {
            Auth::logout();
            Cookie::queue(Cookie::forget(self::COOKIE_LOGIN));
        }

        // 運用者に退会確認メール送信
        $options = ['user_id' => $user->user_id, 'name' => $user->name, 'nickname' => $user->nickname,
            'reasons' => $withdrawal_reasons];
        try {
            $mailable = new \App\Mail\Support('withdrawal_complite', $options);
            Mail::send($mailable);
        } catch (\Exception $e) {
        }

        return redirect(route('withdrawals.store'));
    }
}
