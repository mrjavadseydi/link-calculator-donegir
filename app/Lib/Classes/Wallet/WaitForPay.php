<?php
namespace App\Lib\Classes\Wallet;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\PayOutRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class WaitForPay extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="message"&&get_state($this->chat_id)=="receive_wallet");
    }

    public function handel()
    {
        //validate number user send
        if (!is_numeric($this->text)||$this->text<0){
            return sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>config('robot.not_number'),
            ]);
        }
        if (get_wallet($this->user->id)<$this->text){
            return sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>config('robot.not_enough'),
            ]);
        }
        $wallet = add_wallet($this->user->id,-$this->text);
        $payout = PayOutRequest::query()->create([
            'amount'=>$this->text,
            'status'=>0,
            'account_id'=>$this->user->id,
            'wallet'=>$wallet->id,
            'msg_id'=>0
        ]);
        $str = "شماره پیگیری  : ".$payout->id;
        $str.="\n";
        $str.="مبلغ :‌ ".number_format($payout->amount);
        $str.="\n";
        $str.="شماره شبا :‌ ".$this->user->shaba;
        $str.="\n";
        $str.="شماره کارت :‌ ".$this->user->card;
        $str.="\n";
        $str.="نام صاحب حساب :‌ ".$this->user->name;

       $msg =  sendMessage([
            'chat_id'=>config('telegram.payout'),
            'text'=>$str,
            'reply_markup'=>payoutMenu()
        ]);
        $payout->msg_id = $msg['message_id'];
        $payout->save();
        $str = "شماره پیگیری  : ".$payout->id;
        $str.="\n";
        $str.="مبلغ :‌ ".number_format($payout->amount);
        $str.="\n";
        $str.="پس از بررسی پرداخت خواهد شد";
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$str,
            'reply_markup'=>backKey()
        ]);
        set_state($this->chat_id,"asd");
    }
}
