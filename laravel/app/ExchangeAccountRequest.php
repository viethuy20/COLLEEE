<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;

class ExchangeAccountRequest extends Model
{
    protected $fillable = ['user_id', 'type', 'session_key', 'request', 'response', 'created_at', 'updated_at']; //保存したいカラム名が複数の場合

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getExchangeAccountRequest($user_id,$type,$session_key='')
    {
        $sql = self::select('*')
        ->from('exchange_account_requests')
        ->where('user_id', '=', $user_id)
        ->where('type',  '=', $type);
        if(!empty($session_key)){
            $sql->where('session_key',  '=', $session_key);
        }

        $exchange_account_data = $sql->get();

        if($exchange_account_data->isEmpty()){
            return null;
        }

        return $exchange_account_data;
    }

    public function createExchangeAccountRequest($user_id,$type,$session_key)
    {
        $exchange_account_requests = new self();

        $exchange_account_data = $this->getExchangeAccountRequest($user_id,$type);

        if($exchange_account_data ===  null){
            $exchange_account_requests->create([
                'type' => $type,
                'user_id'  => $user_id,
                'session_key'  => $session_key,
                'request'  => '',
                'response'  => '',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }else{
            $exchange_account_requests->where('user_id', '=', $user_id)
                ->where('type',  '=', $type)
                ->update([
                'session_key'  => $session_key,
                'request'  => '',
                'response'  => '',
                'updated_at' => Carbon::now(),]);
        }
    }
}