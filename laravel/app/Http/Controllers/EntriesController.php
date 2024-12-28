<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Mail;
use Validator;

use App\Device\Device;
use App\EmailToken;
use App\FriendReferralBonusSchedule;
use App\Http\Middleware\Phone;
use App\Http\Middleware\SaveCookie;
use App\LineAccount;
use App\OstToken;
use App\User;
use App\UserFriendReferralBonusPoint;
use App\Services\Meta;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use App\EntryDuplicateMailLog;

class EntriesController extends Controller
{
    /** ユーザー登録セッションキー. */
    const ENTRY_USER_SESSION_KEY = 'entry_user';

    /** session line id*/
    const ENTRY_USER_SESSION_LINE_ID = 'entry_user_line';

    /** session google id*/
    const ENTRY_USER_SESSION_GOOGLE_ID = 'entry_user_google';

    /** session line access token*/
    public const ENTRY_USER_SESSION_LINE_ACESS_TOKEN = 'entry_user_line_access_token';
    public const ENTRY_USER_SESSION_GOOGLE_ACESS_TOKEN = 'entry_user_google_access_token';

    const LOGIN_DEFAULT = 0;
    const LOGIN_LINE = 1;
    const LOGIN_GOOGLE = 2;

    /** cookie login */
    const COOKIE_SOCIAL_CALLBACK = 'cookie_social_callback';
    const COOKIE_LOGIN = 'cookie_login';
    const COOKIE_EXPIRE = 43200;

    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * メール送信.
     * @param Request $request {@link Request}
     */
    public function postSend(Request $request)
    {
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('entries.send');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

        //
        $this->validate(
            $request,
            [
                'email' => ['required', 'custom_email:1', 'custom_email_unblock'],
                'consent' => ['required', 'boolean', 'in:1'],
            ],
            [
                'email.custom_email' => 'メールアドレスが不正な書式です',
                'email.custom_email_unblock' => '利用が不可能なメールアドレスです',
                'consent.required' => '「GMOポイ活会員利用規約」及び「個人情報の取扱いについて」を確認の上同意にチェックを入れて下さい。',
                'consent.boolean' => '「GMOポイ活会員利用規約」及び「個人情報の取扱いについて」を確認の上同意にチェックを入れて下さい。',
                'consent.in' => '「GMOポイ活会員利用規約」及び「個人情報の取扱いについて」を確認の上同意にチェックを入れて下さい。',
            ],
            [
                'email' => 'メールアドレス',
                'consent' => '個人情報の取り扱い'
            ]
        );
        // メールアドレス取得
        $email = email_unquote($request->input('email'));

        // 既に同一メールアドレスのユーザーが存在する場合、正常終了したように見せかける
        if (!User::checkUnique(['email' =>$email])) {
            $entryDuplicateMailLog = new EntryDuplicateMailLog();
            $entryDuplicateMailLog->insertLog($email);
            return view('entries.send', ['email' => $email,'application_json' => $application_json]);
        }

        // 追加データ取得
        $data = SaveCookie::getData();

        // メールトークンID取得
        $email_token_id = EmailToken::createToken($email, EmailToken::CREATE_TYPE, $data);

        // メールトークン発行失敗
        if (!isset($email_token_id)) {
            return redirect()->back()
                ->with('message', '登録作業に失敗しました。');
        }

        // メール送信を実行
        $options = ['email_token_id' => $email_token_id];
        try {
            $mailable = new \App\Mail\Colleee($email, 'entry', $options);
            Mail::send($mailable);
        } catch (\Exception $e) {
        }
        return view('entries.send', ['email' => $email, 'email_token_id' => $email_token_id,'application_json' => $application_json]);
    }

    /**
     * エラーリダイレクトを実行.
     * @param string $message メッセージ
     */
    private function redirectError(string $message)
    {
        session()->forget(self::ENTRY_USER_SESSION_KEY);
        $back = [
            'url' => route('entries.index'),
            'label' => '会員登録をやり直す',
            'message' => "セキュリティ保護のため、登録を終了させていただきます。\nお手数ですが、初めから会員登録をやり直してください。",
        ];
        return redirect(route('error'))
            ->with('back', $back)
            ->with('message', $message);
    }

    const INIT_PROGRESS = 0;
    const BASE_PROGRESS = 1;
    const TEL_PROGRESS = 2;
    const ALL_PROGRESS = 3;

    private function checkProgress(int $progress = self::INIT_PROGRESS)
    {
        // 登録ユーザー情報取得
        $entry_user = session()->get(self::ENTRY_USER_SESSION_KEY);
        // セッションにメールアドレスが登録されていない場合
        if (!isset($entry_user['email'])) {
            return $this->redirectError("トークンの有効期限が\n切れています。");
        }

        // 既存のユーザーを取得
        $cur_user = User::where('email', '=', $entry_user['email'])->ofEnable()
            ->first();

        // 既存のユーザーが存在していた場合
        if (isset($cur_user->id)) {
            return $this->redirectError('メールアドレスが重複するため登録を実行できません。');
        }

        // 基本情報入力前
        if ($progress < self::BASE_PROGRESS) {
            return null;
        }
        // セッションに電話番号が登録されていない場合
        if (!isset($entry_user['tel'])) {
            return redirect(route('entries.create'));
        }
        // 電話番号確認前
        if ($progress < self::TEL_PROGRESS) {
            return null;
        }


        // 発信認証がまだ完了していない場合
        if (!Phone::authenticate($entry_user['tel'])) {
            return redirect(route('entries.confirm'))->with('error_phone', 'true');
        }
        return null;
    }

    /**
     * メールトークン受け取り.
     * @param Request $request {@link Request}
     * @param string $email_token_id メールトークンID
     */
    public function create(Request $request, string $email_token_id = null)
    {
        // ログインしていた場合はログアウトする
        if (Auth::check()) {
            Auth::logout();
        }
        $now = Carbon::now();

        $entry_user = [];
        if($request->r == 'entry-tel'){//電話番号認証からの遷移
            $entry_user = session()->get(self::ENTRY_USER_SESSION_KEY);
        }

        if (isset($email_token_id)) {
            // セッション初期化
            session()->forget(self::ENTRY_USER_SESSION_KEY);
            session()->forget(self::ENTRY_USER_SESSION_LINE_ID);
            session()->forget(self::ENTRY_USER_SESSION_GOOGLE_ID);
            // メールトークン取得
            $email_token = EmailToken::ofEnable(EmailToken::CREATE_TYPE, $email_token_id)->first();

            // メールトークンが存在しなかった場合
            if (!isset($email_token->id)) {
                return $this->redirectError("トークンの有効期限が\n切れています。");
            }

            $entry_user['email'] = $email_token->email;
            session()->put(self::ENTRY_USER_SESSION_KEY, $entry_user);
            if (!empty($request->line_id)) {
                session()->put(self::ENTRY_USER_SESSION_LINE_ID, ['line_id' => $request->line_id]);
            }
            if (!empty($request->google_id)) {
                session()->put(self::ENTRY_USER_SESSION_GOOGLE_ID, ['google_id' => $request->google_id]);
            }
            if (isset($email_token->data)) {
                SaveCookie::saveData(json_decode($email_token->data));
            }
        }
        // 進捗確認
        $res = $this->checkProgress();
        if (isset($res)) {
            return $res;
        }

        // 登録ユーザー情報取得
        $line = session()->get(self::ENTRY_USER_SESSION_LINE_ID);
        $google = session()->get(self::ENTRY_USER_SESSION_GOOGLE_ID);
        $entry_user['prefecture_id'] = $request->old('prefecture_id', $entry_user['prefecture_id'] ?? 13);
        $entry_user['birthday'] = $request->old('birthday', $entry_user['birthday'] ??
                ['year' => $now->year - 6, 'month' => $now->month, 'day' => $now->day]);
        $entry_user['sex'] = $request->old('sex', $entry_user['sex'] ?? null);
        $entry_user['carriers'] = $request->old('carriers', $entry_user['carriers'] ?? null);
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('entries.create');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';
        if(strpos($request->fullUrl(),'?r=entry-tel') === false){
            session(['previous_page_url' => $request->fullUrl()]);
        }else{
            $previous_page_url = str_replace('?r=entry-tel','',$request->fullUrl());
            session(['previous_page_url' => $previous_page_url]);
        }
        return view('entries.create', ['entry_user' => $entry_user,'application_json' => $application_json]);
    }

    /**
     * ユーザー情報登録確認.
     * @param Request $request {@link Request}
     */
    public function postConfirm(Request $request)
    {
        $validateMessages = [
            'prefecture_id.between' => ':attributeを選択してください',
            'birthday.custom_datetime_array' => ':attributeが不正です',
            'sex.in' => ':attributeを選択して下さい',
            'tel.colleee_tel' => '電話番号の書式が不正です。再度入力してください',
            'tel.confirmed' => '確認用電話番号が一致しません。再度入力して下さい',
            'minor-consent.required' => 'チェックを入力して下さい。',
            'minor-consent.boolean' => 'チェックを入力して下さい。',
            'minor-consent.in' => 'チェックを入力して下さい。',];
        $validateAttributes = ['email' => 'メールアドレス',
            'prefecture_id' => '都道府県',
            'birthday' => '生年月日',
            'tel' => '電話番号',
            'sex' => '性別',
            'minor-consent' => '保護者の同意',];
        $role = [
            'prefecture_id' => ['nullable', 'integer', 'between:1,47'],
            'birthday' => ['required', 'custom_datetime_array'],
            'sex' => ['nullable', 'in:0,1,2'],
            'tel' => ['required', 'colleee_tel', 'confirmed'],
        ];
        if (empty($request->line_id) && empty($request->google_id)) {
            $validateMessages['password.colleee_password'] = 'パスワードに使用できない文字が入力されています。再度入力して下さい';
            $validateMessages['password.confirmed'] = '確認用パスワードが一致しません。再度入力して下さい';
            $validateAttributes['password'] = 'パスワード';
            $role['password'] = ['required', 'colleee_password', 'confirmed'];
            // バリデーション
            $status = $this->validate(
                $request,
                $role,
                $validateMessages,
                $validateAttributes
            );
        } else {
            $this->validate(
                $request,
                $role,
                $validateMessages,
                $validateAttributes
            );

        }

        $base_date = Carbon::now()->addYears(-18)->startOfDay();
        $birthday = Carbon::parse(sprintf(
            "%04d-%02d-%02d 00:00:00",
            $request->input('birthday.year'),
            $request->input('birthday.month'),
            $request->input('birthday.day')
        ));
        // 未成年の場合は、同意確認
        if ($base_date->lt($birthday)) {
            // 追加バリデーション
            $validator = Validator::make(
                $request->only('minor-consent'),
                ['minor-consent' => ['required', 'boolean', 'in:1'],],
                $validateMessages,
                $validateAttributes
            );

            //
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
        }
        $entry_user_confirm = ['email' => $request->email, 'tel' => $request->tel];
        session()->put(self::ENTRY_USER_SESSION_KEY, $entry_user_confirm);
        // 進捗確認
        $res = $this->checkProgress();
        if (isset($res)) {
            return $res;
        }
        $entry_user = session()->get(self::ENTRY_USER_SESSION_KEY);
        //carriers
        if (!empty($request->carriers))    {
            if($request->carriers == 'その他' && !empty($request->carriers_other)) {
                $entry_user['carriers'] = ($request->carriers . ' | ' . htmlspecialchars($request->carriers_other));
            } else{
                $entry_user['carriers'] = $request->carriers;
            }
        }
        $entry_user = array_merge($entry_user, $request->only('password', 'prefecture_id', 'birthday', 'sex', 'tel', 'line_id','invitation_code', 'google_id'));
        session()->put(self::ENTRY_USER_SESSION_KEY, $entry_user);
        if (!empty($request->line_id)) {
            session()->put(self::ENTRY_USER_SESSION_LINE_ID, ['line_id' => $request->line_id]);
        }
        if (!empty($request->google_id)) {
            session()->put(self::ENTRY_USER_SESSION_GOOGLE_ID, ['google_id' => $request->google_id]);
        }
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('entries.confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

        return view('entries.confirm', ['entry_user' => $entry_user,'application_json' => $application_json]);
    }

    /**
     * ユーザー情報登録確認.
     * @param Request $request {@link Request}
     */
    public function getConfirm(Request $request)
    {
        // 進捗確認
        $res = $this->checkProgress(self::BASE_PROGRESS);
        if (isset($res)) {
            return $res;
        }
        // 登録ユーザー情報取得
        $entry_user = session()->get(self::ENTRY_USER_SESSION_KEY);
        $line_id = session()->get(self::ENTRY_USER_SESSION_LINE_ID);
        $google_id = session()->get(self::ENTRY_USER_SESSION_GOOGLE_ID);
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('entries.confirm');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

        return view('entries.confirm', [
            'entry_user' => $entry_user, 
            'line_id' => $line_id,
            'google_id' => $google_id,
            'application_json' => $application_json
        ]);
    }

    /**
     * 発信認証確認.
     * @param Request $request {@link Request}
     */
    public function confirmTel(Request $request)
    {
        // 進捗確認
        $res = $this->checkProgress(self::BASE_PROGRESS);
        if (isset($res)) {
            return $res;
        }

        $entry_user = session()->get(self::ENTRY_USER_SESSION_KEY);
        $line_id = $entry_user['line_id'];
        $google_id = $entry_user['google_id'];
        if((!empty($line_id) || !empty($google_id)) && empty(session()->get('previous_page_url'))){
            session(['previous_page_url' => route('entries.create')]);
        }

        // 発信認証が完了している場合
        if (Phone::authenticate($entry_user['tel'])) {
            return redirect(route('entries.question'));
        }
        // 登録ユーザー情報取得
        // トークン発行
        if (!Phone::create(Phone::CREATE_USER_SESSION_KEY, $entry_user['tel'])) {
            return redirect(route('entries.confirm'))->with('error_phone', 'true');
        }
        // トークン取得
        $ost_token = Phone::find(Phone::CREATE_USER_SESSION_KEY);
        // トークン作成に失敗した場合
        if (!isset($ost_token->id)) {
            return redirect(route('entries.confirm'))->with('error_phone', 'true');
        }
        //
        //bredcrum
         $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
         $application_json = '';
         $position = 1;
         foreach($arr_breadcrumbs as $key => $val) {
             $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
             $position++;
         }
         $link = route('entries.confirm_tel');
         $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

        return view('entries.confirm_tel', ['ost_token' => $ost_token,'application_json' => $application_json]);
    }

    /**
     * 発信認証実施.
     * @param Request $request {@link Request}
     */
    public function authTel(Request $request)
    {
        // 進捗確認
        $res = $this->checkProgress(self::BASE_PROGRESS);
        if (isset($res)) {
            return $res;
        }

        $entry_user = session()->get(self::ENTRY_USER_SESSION_KEY);

        // 発信認証が完了している場合
        if (Phone::authenticate($entry_user['tel'])) {
            return redirect(route('entries.question'));
        }

        // 認証
        $res = Phone::attempt(Phone::CREATE_USER_SESSION_KEY);
        switch ($res) {
            case Phone::ERROR_STATUS:
                return redirect(route('entries.confirm'))->with('error_phone', 'true');
            case Phone::WAITING_STATUS:
                return redirect(route('entries.confirm_tel'))->with('error_phone', 'true');
            default:
                break;
        }
        return redirect(route('entries.question'));
    }

    /**
     * アンケート.
     */
    public function question()
    {
        // 進捗確認
        $res = $this->checkProgress(self::ALL_PROGRESS);
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('entries.question');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

        return $res ?? view('entries.question',['application_json' => $application_json]);
    }

    /**
     * ユーザー情報登録.
     * @param Request $request {@link Request}
     */
    public function store(Request $request)
    {
        // 進捗確認
        $res = $this->checkProgress(self::ALL_PROGRESS);
        if (isset($res)) {
            return $res;
        }
        session()->forget('google_user');
        session()->forget('previous_page_url');
        $entry_user = session()->get(self::ENTRY_USER_SESSION_KEY);
        $entry_user_line = session()->get(self::ENTRY_USER_SESSION_KEY);
        $entry_user_google = session()->get(self::ENTRY_USER_SESSION_KEY);
        $email = $entry_user['email'];
        $line_id = $entry_user_line['line_id'];
        $google_id = $entry_user_google['google_id'];
        // トランザクション開始
        DB::beginTransaction();

        try {
            // ユーザー情報を保存
            $user = new User();
            $user->email = $email;
            $save_cookie_data = SaveCookie::getData();
            $user->promotion_id = $save_cookie_data->pr_id ?? 0;
            $user->password = (!empty($entry_user['password'])) ? app()->make('hash')->make($entry_user['password']) : '';
            $user->birthday = Carbon::parse(sprintf(
                "%04d-%02d-%02d 00:00:00",
                $entry_user['birthday']['year'],
                $entry_user['birthday']['month'],
                $entry_user['birthday']['day']
            ));
            $user->prefecture_id = $entry_user['prefecture_id'] ?? null;
            $user->carriers = $entry_user['carriers'] ?? null;
            $user->sex = $entry_user['sex'] ?? null;
            $user->tel = $entry_user['tel'];
            $user->q1 = $request->filled('q1') ? $request->input('q1') : 0;
            $user->q2 = $request->filled('q2') ? $request->input('q2') : 0;
            $user->sp = (Device::getDeviceId() == 1) ? 0 : 1;
            $user->line_id = $line_id ? $line_id : '';

            $user->google_id = $google_id ? $google_id : '';
            $user->email_magazine = 1;
            $user->tel = $entry_user['tel'];
            // 友達コード
            if (isset($entry_user['invitation_code'])) {
                $entry_user['invitation_code'] = (int)substr($entry_user['invitation_code'], 1);
                $friend_user = User::where('id', '=', $entry_user['invitation_code'])
                        ->first();
                if (isset($friend_user->id)) {
                    $user->friend_user_id = $entry_user['invitation_code'];
                }
            }

            elseif (isset($save_cookie_data->fid)) {
                $friend_user = User::where('friend_code', '=', $save_cookie_data->fid)
                        ->first();
                if (isset($friend_user->id)) {
                        $user->friend_user_id = $friend_user->id;
                    }
            }
            $device_ip = Device::getIp();
            $res = $user->createUser($device_ip, $request->header('User-Agent'));
            //失敗した場合
            if (!$res) {
                // ロールバック
                DB::rollBack();
                return $this->redirectError("登録作業に失敗しました。");
            }
            $request->session()->forget(self::ENTRY_USER_SESSION_KEY);
            //メール送信を実行
            $options = ['user_name' => $user->name];
            try {
                $mailable = new \App\Mail\Colleee($email, 'entry_complite', $options);
                Mail::send($mailable);
            } catch (\Exception $e) {
            }

            // メールトークン削除
            EmailToken::removeTokens($email, EmailToken::CREATE_TYPE);
            $line_token = session()->get(self::ENTRY_USER_SESSION_LINE_ACESS_TOKEN);
            $line = LineAccount::firstOrNew(['user_id' => $user->id]);
            $line->line_id = $line_id;
            $line->token = $line_token;
            $line->save();
            // 友達コードで招待されたユーザーの場合
            if (isset($user->friend_user_id)) {
                // 友達紹介報酬スケジュール
                $set_date = date('Y-m-d H:i:s');
                $first_data = FriendReferralBonusSchedule::Enable()->GetDate($set_date)->OrderByDescId()->first();
                // ユーザー友達紹介報酬情報
                $user_friend_referral_bonus_point = UserFriendReferralBonusPoint::create([
                        'user_id'                              => $user->id,
                        'friend_user_id'                       => $user->friend_user_id,
                        'friend_referral_bonus_schedule_id'    => $first_data['id'],
                        'name'                                 => $first_data['name'],
                        'reward_condition_point'               => $first_data['reward_condition_point'],
                        'friend_referral_bonus_point'          => $first_data['friend_referral_bonus_point'],
                ]);
            }

            Auth::login($user, true);
            // 指定時間内に重複するIPで登録された場合は全ロックに更新
            $basetime = Carbon::now();
            if (config('app.env') == 'production' && !User::checkDuplicateIp($device_ip, $basetime)) {
                if (!User::lockUsers($device_ip, $basetime)) {
                    \Log::error('Failed to lock member', [
                        'basetime' => $basetime,
                        'ip' => $device_ip,
                        'user_id' => $user->id,
                    ]);
                }
            }

            // トランザクションをコミット
            DB::commit();

            // セッションから 'last_visited_program_page' もしくは 'referrer_url' の値を取得
            $registrationSource = session('last_visited_program_page', session('referrer_url', route('entries.debut')));

            // セッションから 'last_visited_program_page' と 'referrer_url' を削除
            session()->forget('last_visited_program_page');
            session()->forget('referrer_url');
            //bredcrum
            $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
            $application_json = '';
            $position = 1;
            foreach($arr_breadcrumbs as $key => $val) {
                $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
                $position++;
            }
            $link = route('entries.store');
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

            if (Cookie::has(self::COOKIE_LOGIN)) {
                Cookie::queue(Cookie::forget(self::COOKIE_LOGIN));
            }

            if (Cookie::has(self::COOKIE_SOCIAL_CALLBACK)) {
                $cookie = Crypt::decryptString(Cookie::get(self::COOKIE_SOCIAL_CALLBACK));
                switch (true) {
                    case $cookie == self::LOGIN_LINE && $line_id:
                        Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_LINE), self::COOKIE_EXPIRE);
                        break;
        
                    case $cookie == self::LOGIN_GOOGLE && $google_id:
                        Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_GOOGLE), self::COOKIE_EXPIRE);
                        break;
                }
                
            } else {
                Cookie::queue(self::COOKIE_LOGIN, Crypt::encryptString(self::LOGIN_DEFAULT), self::COOKIE_EXPIRE);
            }

            if (Cookie::has(self::COOKIE_SOCIAL_CALLBACK)) {
                Cookie::queue(Cookie::forget(self::COOKIE_SOCIAL_CALLBACK));
            }

            return view('entries.store', ['registration_source' => $registrationSource,'application_json' => $application_json]);

        } catch (\Exception $e) {
            // 例外が発生した場合はロールバック
            DB::rollBack();
            if (Cookie::has(self::COOKIE_LOGIN)) {
                Cookie::queue(Cookie::forget(self::COOKIE_LOGIN));
            }
            \Log::error('Failed to register user', [
                'exception' => $e,
            ]);
            return $this->redirectError("登録作業に失敗しました。");
        }
    }

    public function createRegist(Request $request, string $email_token_id = null)
    {
        // ログインしていた場合はログアウトする
        if (Auth::check()) {
            Auth::logout();
        }
        $now = Carbon::now();
        if (isset($email_token_id)) {
            // セッション初期化
            session()->forget(self::ENTRY_USER_SESSION_KEY);

            // メールトークン取得
            $email_token = EmailToken::ofEnable(EmailToken::CREATE_TYPE, $email_token_id)->first();

            // メールトークンが存在しなかった場合
            if (!isset($email_token->id)) {
                return $this->redirectError("トークンの有効期限が\n切れています。");
            }

            $entry_user = ['email' => $email_token->email];
            session()->put(self::ENTRY_USER_SESSION_KEY, $entry_user);
            if (isset($email_token->data)) {
                SaveCookie::saveData(json_decode($email_token->data));
            }
        }

        // 進捗確認
        $res = $this->checkProgress();
        if (isset($res)) {
            return $res;
        }

        // 登録ユーザー情報取得
        $entry_user = session()->get(self::ENTRY_USER_SESSION_KEY);
        $entry_user['prefecture_id'] = $request->old('prefecture_id', $entry_user['prefecture_id'] ?? 13);
        $entry_user['birthday'] = $request->old('birthday', $entry_user['birthday'] ??
            ['year' => $now->year - 6, 'month' => $now->month, 'day' => $now->day]);
        $entry_user['sex'] = $request->old('sex', $entry_user['sex'] ?? 1);
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('entries.create');
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "新規会員登録", "item": "' . $link . '"},';

        return view('entries.create', ['entry_user' => $entry_user,'application_json' => $application_json]);
    }

    /**
     * 発信認証ステータス取得API
     */
    public function apiCheckAuthTel($token,$p_tel,Request $request)
    {
            $return_sql = OstToken::ofEnableOrError($token)
            ->where('tel', '=', $p_tel);
            $return = $return_sql->first();
            $return_status = false;
            if(!empty($return) && $return->status == OstToken::CREATE_STATUS){
                $new_body = json_decode($return->new_body);
                if(isset($new_body->response) && $new_body->response === 'success'){
                    $ost_token = OstToken::find($token);
                    if($ost_token->checkStatus() === OstToken::SUCCESS_CHECK_STATUS){
                        $return_status = true;
                    }
                }
            }elseif(!empty($return) && $return->status == OstToken::SUCCESS_STATUS){
                $return_status = true;
            }

            $response = [
                'result' => $return_status
            ];

            return response()->json($response, 200);

    }
}
