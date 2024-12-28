<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\ExchangeAccounts;
use App\ExchangeRequest;
use App\Services\CommonService;
use App\Services\Kdol\KdolService;
use App\ExchangeRequestCashbackKey;
use Carbon\Carbon;



class KdolWebhookController extends Controller
{

    private $kdolService;
    private $commonService;
    private $response_code;

    public function __construct(KdolService $kdolService,CommonService $commonService)
    {
        $this->kdolService = $kdolService;
        $this->commonService = $commonService;
        $this->response_code = config('kdol.response_chashback_code');
    }

    public function user(Request $request)
    {
        // $data = $request->all();
        
        // if($this->kdolService->checkAccountResponse($data)){
        //     $user_id = $this->commonService->getExchangeAccountUserId($data['gmo_id'],ExchangeRequest::KDOL_TYPE);
        //     if($data['status']===0 && $data['insert_type']===0){
        //         $exchange_accounts = new ExchangeAccounts();
        //         $exchange_accounts->createExchangeAccounts($user_id,ExchangeRequest::KDOL_TYPE,$data['kdol_id'],json_encode($data));
        //     }
        //     $this->commonService->api_log(['user_id'=>$user_id,'exchange_request_id'=>'','type'=>ExchangeRequest::KDOL_TYPE,'request'=>'','response'=>$data,'api_name'=>'/webhook/user','status_code'=>'']);
        // }
        
        return response()->json(['status' => 'ok']);
    }

    public function chashback(Request $request)
    {
        // $data = $request->all();
        // if($this->kdolService->checkChashbackResponse($data)){
        //     $user_id = $this->commonService->getExchangeAccountUserId($data['kdol_id'],ExchangeRequest::KDOL_TYPE);

        //     $this->commonService->api_log(['user_id'=>$user_id,'exchange_request_id'=>'','type'=>ExchangeRequest::KDOL_TYPE,'request'=>'','response'=>$data,'api_name'=>'/webhook/user','status_code'=>'']);
            
        //     $exchange_request_id = $this->commonService->getExchangeRequestId($data['transaction_id']);

        //     if($exchange_request_id && $user_id){
        //         $exchange_request = ExchangeRequest::ofKdol()
        //         ->OfExchangeWaiting()//交換申請中
        //         ->where('id', $exchange_request_id)
        //         ->where('user_id', $user_id)
        //         ->first();

        //         if(!$exchange_request){
        //             return response()->json(['status' => 'ok']);
        //         }

        //         $exchange_request->response_code = $exchange_request->response_code.':'.$this->response_code[$data['status']];

        //         if($data['status']===1){//受付中
        //             $exchange_request->save();

        //         }elseif($data['status']===2){//成功
        //             $exchange_request->approvalRequest();

        //         }elseif($data['status']===3){//失敗
        //             $exchange_request->rollbackRequest();

        //         }
        //     }
            
        // }
        
        return response()->json(['status' => 'ok']);
    }

}