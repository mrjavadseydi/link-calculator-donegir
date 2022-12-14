<?php
namespace App\Lib\Classes\Wallet;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\PayOutRequest;
use App\Models\Wallet;
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
        $calc_amount = Wallet::where('account_id', $this->user->id)->where('created_at', '<', now()->subDay())->orderBy('id', 'desc')
            ->first()->balance??0;
        $total_wait = PayOutRequest::where('account_id', $this->user->id)->where('created_at','>',now()->subDay())->sum('amount');
        $total = $calc_amount - $total_wait;
        $avaible = min(get_wallet($this->user->id)-$total_wait,$total);
        $avaible = $avaible<0?0:$avaible;
        if ($avaible<$this->text){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>config('robot.not_enough')
            ]);
            return false;
        }

        if ($this->text<10000){
            return sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>config('robot.min_amount'),
            ]);
        }
        $wallet = add_wallet($this->user->id,-$this->text,"برداشت موجودی");
        if ($this->text>=1_000_000){
            $this->text = $this->text-1_000;
        }else{
            $this->text = $this->text-600;
        }
        $payout = PayOutRequest::query()->create([
            'amount'=>$this->text,
            'status'=>0,
            'account_id'=>$this->user->id,
            'wallet_id'=>$wallet->id,
            'msg_id'=>0
        ]);
        $str = "شماره پیگیری  : ".$payout->id;
        $str.="\n";
        $str.="مبلغ :‌ ".number_format($payout->amount);
        $str.="تومان";
        $str.="\n";
        $str.="شماره شبا :‌ ".$this->user->shaba;
        $str.="\n";
        $str.="شماره کارت :‌ ".$this->user->card;
        $str.="\n";
        $str.="نام صاحب حساب :‌ ".$this->user->name;
        $str.="\n";
        $str.="یوزر نیم : ".'@'.$this->user->username;
        $str.="\n";
        $str.="چت ایدی : ".$this->chat_id;

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
        $str.="تومان";
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
