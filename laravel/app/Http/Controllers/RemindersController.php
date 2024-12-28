<?php
namespace App\Http\Controllers;

use Auth;
use DB;
use Illuminate\Http\Request;

use App\Device\Device;
use App\EmailToken;
use App\Http\Middleware\Authenticate;
use App\User;

class RemindersController extends Controller
{
    /**
     * エラーリダイレクトを実行.
     * @param string $message メッセージ
     */
    private function redirectError(string $message)
    {
        $back = ['url' => route('reminders.index'), 'label' => '変更作業をやり直す',
            'message' => "セキュリティ保護のため、変更作業を終了させていただきます。\nお手数ですが、初めから変更作業をやり直してください。"];
        return redirect(route('error'))
            ->with('back', $back)
            ->with('message', $message);
    }

    /**
     * メールアドレス確認.
     * @param Request $request {@link Request}
     */
    public function confirm(Request $request)
    {
        //
        $this->validate(
            $request,
            ['email' => ['required', 'custom_email:1',],],
            [
                'email.required' => 'メールアドレスを入力して下さい',
                'email.custom_email' => 'メールアドレスが不正な書式です',
            ],
            ['email' => 'メールアドレス',]
        );
        // メールアドレス取得
        $email = email_unquote($request->input('email'));

        return view('reminders.confirm', ['email' => $email]);
    }

    /**
     * メール送信.
     * @param Request $request {@link Request}
     */
    public function send(Request $request)
    {
        // メールアドレス取得
        $email = $request->input('email');

        // ユーザー情報を取得
        $user = User::where('email', '=', $email)
            ->ofEnable()
            ->first();

        // ユーザーが存在する場合
        $email_token_id = null;
        if (isset($user->id)) {
            // 追加データ取得
            $data = (object)['user_id' => $user->id];

            // メールトークンID取得
            $email_token_id = EmailToken::createToken($email, EmailToken::REMINDER_TYPE, $data);

            // メールトークン発行失敗
            if (!isset($email_token_id)) {
                return $this->redirectError("変更作業に失敗しました。");
            }

            // メール送信を実行
            $options = ['email_token_id' => $email_token_id, 'user' => $user];
            try {
                $mailable = new \App\Mail\Colleee($email, 'confirm_password', $options);
                \Mail::send($mailable);
            } catch (\Exception $e) {
            }
        }

        return view('reminders.send', ['email_token_id' => $email_token_id]);
    }

    /**
     * パスワード入力.
     * @param string $email_token_id メールトークンID
     */
    public function password(string $email_token_id)
    {
        // ログインしていた場合はログアウトする
        if (Auth::check()) {
            Auth::logout();
        }
        // メールトークン取得
        $email_token = EmailToken::ofEnable(EmailToken::REMINDER_TYPE, $email_token_id)->first();

        // メールトークンが存在しなかった場合
        if (!isset($email_token->id)) {
            return $this->redirectError("トークンの有効期限が\n切れています。");
        }

        return view('reminders.password', ['email_token_id' => $email_token_id]);
    }

    /**
     * パスワード変更.
     * @param Request $request {@link Request}
     */
    public function store(Request $request)
    {
        //
        $this->validate(
            $request,
            [
                'email_token_id' => ['required',],
                'password' => ['required', 'colleee_password', 'confirmed',],
            ],
            [
                'password.required' => 'パスワードを入力して下さい',
                'password.confirmed' => 'パスワードが一致しません。再度入力して下さい',
            ],
            [
                'email_token_id' => 'トークン',
                'password' => '新しいパスワード',
            ]
        );

        $email_token_id = $request->input('email_token_id');

        // 追加データ取得
        $email_token = EmailToken::ofEnable(EmailToken::REMINDER_TYPE, $email_token_id)->first();
        // メールトークンIDが取得できない場合
        if (!isset($email_token->id)) {
            return $this->redirectError("トークンの有効期限が\n切れています。");
        }
        $data = json_decode($email_token->data);

        // ユーザー情報を取得
        $user = User::where('id', '=', $data->user_id)
            ->ofEnable()
            ->first();

        // パスワードの更新
        $res = $user->editPassword($request->input('password'), Device::getIp(), $request->header('User-Agent'), true);
        // メールトークン削除
        $email_token->removeToken();

        //
        if (!$res) {
            return redirect()->back()
                ->with('message', "パスワード変更作業に失敗しました");
        }

        // ユーザーをログイン状態にする
        if (!Auth::check()) {
            Auth::login($user, true);
        }

        return view('reminders.store', ['item' => 'パスワード']);
    }

    /**
     * メールアドレス.
     * @param string $email_token_id メールトークンID
     */
    public function email(string $email_token_id)
    {
        // ログインしていた場合はログアウトする
        if (Auth::check()) {
            Auth::logout();
        }

        // メールトークン取得
        $email_token = EmailToken::ofEnable(EmailToken::EMAIL_REMINDER_TYPE, $email_token_id)->first();

        // メールトークンが存在しなかった場合
        if (!isset($email_token->id)) {
            return $this->redirectError("トークンの有効期限が\n切れています。");
        }

        return view('reminders.email', ['email_token_id' => $email_token_id]);
    }

    /**
     * メールアドレス変更.
     * @param Request $request {@link Request}
     */
    public function storeEmail(Request $request)
    {
        //
        $this->validate(
            $request,
            [
                'email_token_id' => ['required',],
                'password' => ['required',],
            ],
            [],
            ['password' => 'パスワード']
        );

        $email_token_id = $request->input('email_token_id');
        $password = $request->input('password');

        // 追加データ取得
        $email_token = EmailToken::ofEnable(EmailToken::EMAIL_REMINDER_TYPE, $email_token_id)->first();
        // メールトークンIDが取得できない場合
        if (!isset($email_token->id)) {
            return $this->redirectError("トークンの有効期限が\n切れています。");
        }
        $data = json_decode($email_token->data);

        // ユーザー取得
        $user = User::where('id', '=', $data->user_id)
            ->ofEnable()
            ->first();

        // 認証実行
        if (!isset($user->id) || !Authenticate::checkPassward($user, $password)) {
            // ログインが失敗した場合
            return redirect()->back()
                ->with('message', "メールアドレス変更作業に失敗しました");
        }

        // メールアドレスの更新
        $res = $user->editEmail($email_token->email, Device::getIp(), $request->header('User-Agent'), true);
        // メールトークン削除
        $email_token->removeToken();

        //
        if (!$res) {
            return redirect()->back()
                ->with('message', "メールアドレス変更作業に失敗しました");
        }

        // ユーザーをログイン状態にする
        Auth::login($user, true);

        return view('reminders.store', ['item' => 'メールアドレス']);
    }
}
