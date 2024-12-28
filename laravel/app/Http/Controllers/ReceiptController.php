<?php

namespace App\Http\Controllers;

use App\Affiriate;
use App\CreditCard;
use App\Device\Device;
use App\Search\CreditCardCondition;
use App\Search\ReceiptCondition;
use App\Services\TentameService;
use Carbon\Carbon;
use Auth;
use Validator;
use Illuminate\Pagination\LengthAwarePaginator;
use WrapPhp;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Services\Meta;

class ReceiptController extends Controller
{
    private $meta;

    public function __construct(Meta $meta)
    {
        $this->meta = $meta;
    }
    /**
     * 認証画面.
     * @param Request $request {@link Request}
     */
    public function index(Request $request, $sort = 1, $page = 1)
    {
        $user = Auth::user();

        $condition = new ReceiptCondition();
        $condition->setParams(['sort' => $sort, 'page' => $page]);
        $paginator = '';
        $data = $this->getItemProjectForTentame($user);
        $secret = $this->getClientSecret($user);
        $listItem = [];
        $dataArr = !empty($data['data']->projects) ? $data['data']->projects : '';
        if ($dataArr) {
            foreach ($dataArr as $key => $val) {
                if (!$val->isRemaining) continue;
                
                if ($val->status + 1 == $sort) {
                    $listItem[$key]['name'] = $val->name;
                    $listItem[$key]['url'] = $val->url;
                    $listItem[$key]['point'] = $val->point ?? 0;
                    $listItem[$key]['startDate'] = $val->startDate;
                    $listItem[$key]['endDate'] = $val->endDate;
                    $listItem[$key]['userCount'] = $val->userCount ?? 0;
                    $listItem[$key]['item_name'] = $val->product->name ?? '';
                    $listItem[$key]['description'] = $val->product->description ?? '';
                    $listItem[$key]['price'] = $val->product->price ?? 0;
                    $listItem[$key]['image'] = $val->product->image ?? '';
                    $listItem[$key]['quantity'] = $val->product->quantity ?? 0;
                }
            }
        }

        if ($listItem) {
            $paginator = $this->getPaginator($listItem, $condition);
        }
        //bredcrum
        $arr_breadcrumbs = $this->meta->setBreadcrumbs(null);
        $application_json = '';
        $position = 1;
        foreach($arr_breadcrumbs as $key => $val) {
            $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "' . $val['title'] . '", "item": "' . $val['link'] . '"},';
            $position++;
        }
        $link = route('receipt.list',['condition' => $condition]);
        $application_json .= '{"@type": "ListItem","position": ' . $position . ', "name": "モニター(レシ活)", "item": "' . $link . '"},';

        return view('receipt.index', ['paginator' => $paginator,
                                        'condition' => $condition,
                                        'secret' => $secret,
                                        'application_json' => $application_json
                                    ]);
    }

//    private function createUserTentame($user)
//    {
//        $birthday = !empty($user->birthday) ? date('Y-m-d', strtotime($user->birthday)) : '';
//        $timestamp = date('Y-m-d H:i:s');
//        $paramsEncode = ['siteUserId' => $user->id, 'gender' => $user->gender, 'dob' => $birthday, 'prefecture' => $user->prefecture, 'timestamp' => $timestamp];
//        $paramsEncode['signature'] = hash("sha512", 'siteUserId=' . $user->id . '&gender=' . $user->gender . '&dob=' . $birthday . '&prefecture=' . $user->prefecture . '&timestamp=' . $timestamp);
//        $data = json_encode($paramsEncode);
//        $clientSecret = openssl_encrypt($data, 'AES-256-CBC', '', $options = 0, openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC')));
//        $params['clientKey'] = config('receipt.CLIENT_KEY');
//        $params['clientSecret'] = $clientSecret;
//        try {
//            $response = (new TentameService())->registerUser($params);
//
//            if (!empty($response['status'])) {
//                return $response;
//            }
//        } catch (\Exception $e) {
//            \Log::info('Create user tentame:' . $e->getMessage());
//        }
//        return false;
//    }

    private function getPaginator($listItem, $condition)
    {
        // Convert data object to collection
        $listItem = collect($listItem);

        // Number of items per page
        $perPage = 10;

        // 総件数取得
        $total = WrapPhp::count($listItem);

        // 件数
        $limit = 10;

        // ページ数
        $page = min(max($condition->getParam('page'), 1), ceil($total / $limit));

        // ページネーション作成
        $paginator = new LengthAwarePaginator($listItem->forPage($page, $perPage), $total, $limit, $page);

        return $paginator;

    }

    private function getItemProjectForTentame($user)
    {
        //	API	リクエストパラメター
        $params['clientKey'] = config('receipt.CLIENT_KEY');
        $params['clientSecret'] = $this->getClientSecret($user);

        try {
            $response = (new TentameService())->getItemProject($params);

            if (!empty($response['status'])) {
                return $response;
            }
        } catch (\Exception $e) {
            \Log::info('Get item project:' . $e->getMessage());
        }
        return false;
    }

    private function getClientSecret($user)
    {
        $clientSecretKey = config('receipt.CLIENT_SECRET_KEY');
        $timestamp = date('Y-m-d H:i:s');
        $device = (Device::getDeviceId() == 1) ? 0 : 1;
        $paramsEncode = ['siteUserId' => $user::getNameById($user->id), 'projectId' => '', 'iframe' => 'yes', 'device' => $device, 'timestamp' => $timestamp];
        $requestParametersJsonString = json_encode($paramsEncode);
        $paramsEncode['signature'] = hash_hmac('sha512', $requestParametersJsonString, $clientSecretKey);

        //	Open SSL (AES-256 /	CBC	/ PADDING_PKCS7) を用いて clientSecret を作成
        $data = json_encode($paramsEncode);
        $cipher = 'aes-256-cbc';
        $ivLength = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);
        $blockSize = $ivLength;
        $paddingSize = $blockSize - (strlen($data) % $blockSize);
        $padding = str_repeat(chr($paddingSize), $paddingSize);
        $paddedValue = $data . substr($padding, 0, $paddingSize);
        $encryptedValue = openssl_encrypt($paddedValue, $cipher, $clientSecretKey, OPENSSL_RAW_DATA, $iv);
        $encryptedValue = $iv . $encryptedValue;

        return bin2hex($encryptedValue);
    }

}
