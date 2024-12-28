<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Carbon\Carbon;
use App\ExchangeRequest;

class ExchangeAccounts extends Model
{
    protected $fillable = ['type', 'user_id', 'number', 'data', 'created_at', 'updated_at', 'deleted_at',]; //保存したいカラム名が複数の場合

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getExchangeAccounts($user_id,$type,$number='')
    {
        $sql = self::select('number', 'data')
        ->from('exchange_accounts')
        ->where('user_id', '=', $user_id)
        ->where('type',  '=', $type)
        ->whereNull('deleted_at');
        if(!empty($number)){
            $sql->where('number',  '=', $number);
        }
        $exchange_account_data = $sql->get();

        if($exchange_account_data->isEmpty()){
            return null;
        }

        return $exchange_account_data;
    }

    public function createExchangeAccounts($user_id,$type,$number,$json_data)
    {
        $exchange_accounts = new self();

        $exchange_account_data = $this->getExchangeAccounts($user_id,$type);

        if($exchange_account_data ===  null){
            $exchange_accounts->create([
                'type' => $type,
                'user_id'  => $user_id,
                'number'  => $number,
                'data' =>  $json_data,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }else{
            $exchange_accounts->where('user_id', '=', $user_id)
                ->where('type',  '=', $type)
                ->whereNull('deleted_at')->update([
                'number'  => $number,
                'data' =>  $json_data,
                'updated_at' => Carbon::now(),]);
        }
    }
}


