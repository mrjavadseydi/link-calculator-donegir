<?php

namespace App\Lib\Classes\Wallet;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;
use App\Models\PayOutRequest;
use App\Models\Prize;
use App\Models\Sponser;
use App\Models\SponserLink;
use App\Models\Wallet;
use Illuminate\Support\Facades\Cache;
use Morilog\Jalali\Jalalian;

class GetWalletDetail extends TelegramOprator
{

    public function initCheck()
    {
        if ($this->message_type == "callback_query") {
            $ex = explode("_", $this->data);
            if ($ex[0] == "wallet") {
                return true;
            }
        }
        return false;
    }

    public function handel()
    {
        $ex = explode("_", $this->data);
        $resp = "";
        if ($ex[1]=="add"){

            $wallets = Wallet::where('account_id',$this->user->id)->where('sponser_id','!=',null)->groupBy('sponser_id')->get('sponser_id');
            foreach ($wallets as $wallet){
                if (!Sponser::find($wallet->sponser_id)){
                    continue;
                }
                $mablagh = Wallet::where('account_id',$this->user->id)
                    ->where('sponser_id',$wallet->sponser_id)->sum('action');
                $last = Wallet::where('account_id',$this->user->id)
                    ->where('sponser_id',$wallet->sponser_id)->orderBy('id','desc')->first()->created_at;
                $last = Jalalian::fromCarbon($last)->format('Y/m/d H:i');
                $sponser = Sponser::find($wallet->sponser_id)->name;
                $mablagh = number_format($mablagh) . " تومان ";
                $resp .="💵 مبلغ:  $mablagh
 🔊 تبلیغ:  $sponser
🕰  تاریخ : $last
〰️〰️〰️
\n";
            }
        }elseif($ex[1]=="mines"){
            $payouts = PayOutRequest::where('account_id',$this->user->id)->orderBy('id','desc')->get();
            foreach ($payouts as $payout){
                $mablagh = number_format($payout->amount) . " تومان ";
                $time = Jalalian::fromCarbon($payout->updated_at)->format('Y/m/d H:i');
                if ($payout->status==0){
                    $status = "🕑در حال بررسی";
                }elseif ($payout->status==1){
                    $status = "✳️پرداخت شده";
                }else{
                    $status = "🔴 رد شده";
                }
                $resp.= "💸مبلغ :  $mablagh
🕰 تاریخ :‌$time
$status
〰️〰️〰️\n";
            }
        }elseif ($ex[1]=="prize"){
            $prizes = Prize::where('account_id',$this->user->id)->orderBy('id','desc')->get();
            if (count($prizes)==0){
                return sendMessage([
                    'chat_id'=>$this->chat_id,
                    'text'=>"شما هنوز هیچ جایزه ای دریافت نکرده اید"
                ]);
            }
            foreach ($prizes as $prize ){
                if($prize->sponser == null){
                    continue;
                }
                $mablagh =number_format( $prize->amount). " تومان ";
                $time =  Jalalian::fromCarbon($prize->updated_at)->format('Y/m/d H:i');
                $tabligh = $prize->sponser->name;
                $resp .="💵 مبلغ: $mablagh
🕰 تاریخ  : $time
🎁 بابت  تبلیغ  $tabligh
〰️〰️〰️\n";
            }
        }

        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$resp,
        ]);
    }
}
