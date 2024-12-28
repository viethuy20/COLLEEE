<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mail;

use App\Device\Device;
use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event;
use FacebookAds\Object\ServerSide\EventRequest;
use FacebookAds\Object\ServerSide\UserData;
use App\Services\Meta;

class InquiriesController extends Controller
{
    /** 問い合わせセッションキー. */
    const INQUIRY_SESSION_KEY = 'inquiry';
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }

    /**
     * 問い合わせ入力.
     * @param Request $request {@link Request}
     * @param int $inquiry_id 問い合わせID
     */
    public function inquiry(Request $request, int $inquiry_id = 0)
    {
        $inquiry_id = $inquiry_id == 9 ? 10 : $inquiry_id;
        $inquiries_map = config('map.inquiries');
        if (!isset($inquiries_map[$inquiry_id])) {
            abort(404, 'Not Found.');
        }
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('inquiries.index',['inquiry_id' => $inquiry_id]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "お問い合わせ", "item": "' . $link . '"},';


        return view('inquiries.index', ['inquiry_id' => $inquiry_id, 'title' => $request->input('title'),'application_json' => $application_json]);
    }

    /**
     * 問い合わせ確認.
     * @param Request $request {@link Request}
     */
    public function confirm(Request $request)
    {
        $inquiries_map = config('map.inquiries');
        $validateRules = [
            'inquiry_id' => ['required', 'integer', 'in:'. implode(',', array_keys($inquiries_map))],
            'email' => ['required', 'custom_email:1',],
            'inquiry_detail' => ['required',],
            'consent' => ['required', 'boolean', 'in:1',],
            'request_timestamp' => ['required',],
        ];

        $inquiry_id = $request->input('inquiry_id');

        // 問い合わせ項目に応じた追加バリデーション
        if ($inquiry_id == '3') {
            // 「ポイント獲得について」を選択
            $validateRules = array_merge(
                $validateRules,
                [
                    'program_name' => ['required',],
                    'payment_number' =>  ['nullable', 'regex:/^[a-zA-Z\d\-]+$/'],
                    'name' => ['required',],
                    'joined_at' => ['nullable', 'custom_datetime_array',],
                    'mail_message' => ['nullable',],
                ]
            );
        }
        
        //
        $this->validate(
            $request,
            $validateRules,
            [
                'inquiry_id.required' => 'お問い合わせ項目を選択して下さい',
                'program_name.required' => '対象広告名を入力して下さい',
                'payment_number.regex' => '決済情報番号が不正な書式です',
                'email.required' => 'メールアドレスを入力して下さい',
                'email.custom_email' => 'メールアドレスが不正な書式です',
                'name.required' => 'お名前を入力して下さい',
                'joined_at.custom_datetime_array' => '参加日時が不正な書式です',
                'inquiry_detail.required' => 'お問い合わせ詳細を入力して下さい',
                'consent.required' => '「個人情報の取り扱いについて」を確認の上同意にチェックを入れて下さい。',
                'consent.boolean' => '「個人情報の取り扱いについて」を確認の上同意にチェックを入れて下さい。',
                'consent.in' => '「個人情報の取り扱いについて」を確認の上同意にチェックを入れて下さい。',
            ],
            [
                'inquiries' => 'お問い合わせ項目',
                'program_name' => '対象広告名',
                'payment_number' => '決済情報番号',
                'email' => 'メールアドレス',
                'name' => 'お名前',
                'joined_at' => '参加日時',
                'inquiry_detail' => 'お問い合わせ詳細',
                'mail_message' => '購入・登録完了メール',
                'consent' => '個人情報の取り扱い',
            ]
        );

        // リクエストの開始時間を取得
        $inquiry = $request->only(['inquiry_id', 'payment_number', 'program_name',
            'email', 'name', 'inquiry_detail', 'mail_message', 'request_timestamp',]);
        $joined_at = null;
        if ($inquiry_id == '3') {
            $joined_at = $request->input('joined_at');
            if (!isset($joined_at['year'])) {
                $joined_at = null;
            } else {
                $joined_at['hour'] = $joined_at['hour'] ?? 0;
            }
        }
        $inquiry['joined_at'] = $joined_at;
        
        // セッションに保存
        session()->put(self::INQUIRY_SESSION_KEY, $inquiry);

        return view('inquiries.confirm', ['inquiry' => $inquiry]);
    }

    /**
     * 問い合わせ完了.
     */
    public function store()
    {
        // セッションを確認
        if (!session()->has(self::INQUIRY_SESSION_KEY)) {
            abort(404, 'Not Found.');
        }

        // セッションから値を取得
        $inquiry = session()->get(self::INQUIRY_SESSION_KEY);

        // 問い合わせ完了時間
        $now = Carbon::now()->format('Y-m-d H:i:s');
        
        // セッションから値を削除
        session()->forget(self::INQUIRY_SESSION_KEY);
       
        $user = Auth::user();
        
        // サポートに問い合わせメール送信
        $inquiry['user_name'] = $user->name ?? null;
        $inquiry['user_agent'] = request()->header('User-Agent');
        $inquiry['ip_address'] = Device::getIp();

        try {
            $mailable = new \App\Mail\Support('inquiry_info', $inquiry);
            $mailable->from(email_quote($inquiry['email']));
            Mail::send($mailable);
        } catch (\Exception $e) {
        }

        return view('inquiries.store', ['inquiry_id' => $inquiry['inquiry_id']]);
    }

    /**
     * Meta(Facebook) Conversion API
     * @param Request $request {@link Request}
     */
    public function ajaxMetaCvApiPost(Request $request) {
        $event_Id = $request->randomId;
        $udata_em = $request->udata_em;
        $user_agent = request()->header('User-Agent');
        $ip_address = Device::getIp();

        //Log::info($event_Id);
        //Log::info($udata_em);
        $access_token = 'EAAKKdiQhvdkBAF1q1avMVeej7EN8HqiBWfbFoErpXOAHnAQyZAKBWgbFaX7Kk2On8NG5pWcNrWXbvxdVsoMQvxwUmmjapgSjWlFLZATIcfTwY5gzFZBUZB9qDPWC5Mq7YZCtgcYj9zbETRRtK7WacpPOkKKxlilEOyB3aklWynG9YqfaHy78w';
        $pixel_id = '5587341028056008';

        $api = Api::init(null, null, $access_token);
        $api->setLogger(new CurlLogger());
        $user_data = (new UserData())
            ->setEmails(array($udata_em))
            ->setClientIpAddress($ip_address)
            ->setClientUserAgent($user_agent);
        $event = (new Event())
            ->setEventName('PageView')
            ->setEventTime(time())
            ->setEventId($event_Id)
            ->setEventSourceUrl('https://colleee.net/inquiries/store')
            ->setUserData($user_data)
            ->setActionSource(ActionSource::WEBSITE);
        $events = array();

        array_push($events, $event);
        $user_data = (new UserData())
            ->setEmails(array($udata_em))
            ->setClientIpAddress($ip_address)
            ->setClientUserAgent($user_agent);
        $event2 = (new Event())
            ->setEventName('Contact')
            ->setEventTime(time())
            ->setEventId($event_Id)
            ->setEventSourceUrl('https://colleee.net/inquiries/store')
            ->setUserData($user_data)
            ->setActionSource(ActionSource::WEBSITE);

        array_push($events, $event2);

        $fb_request = (new EventRequest($pixel_id))
            ->setEvents($events);

        //TestEventCodeを使用する場合Facebook社の方で番号を毎日変更されてしまうのでテストする日に修正すること
        //$fb_request->setTestEventCode('TEST58855');
        $response = $fb_request->execute();

        //Log::info($response->getEventsReceived());
        //Log::info($response->getMessages());
        //Log::info($response->getFbTraceId());
        return response()->json((object)['eventsreceived' => $response->getEventsReceived(), 'fbtraceid' => $response->getFbTraceId()]);
    }



}
