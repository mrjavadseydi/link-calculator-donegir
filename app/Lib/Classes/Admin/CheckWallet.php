<?php

namespace App\Lib\Classes\Admin;

use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\PayOutRequest;
use App\Models\Sponser;
use App\Models\Wallet;

class CheckWallet extends TelegramOprator
{

    public function initCheck()
    {
        return ($this->message_type == "channel_post" && $this->chat_id == config('telegram.sponsers') && strpos($this->text,'/wallet_check')!==false);
    }

    public function handel()
    {


        $chat_id = str_replace('/wallet_check ','',$this->text);
        $account = Account::query()->where('chat_id',$chat_id)->first();
        if(!$account){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"کاربری با این شناسه یافت نشد"
            ]);
            return false;
        }
        $text = "موجودی کیف پول : ".number_format(get_wallet($account->id))." تومان";
        $calc_amount = Wallet::where('account_id', $account->id)->where('created_at', '<', now()->subDay())->orderBy('id', 'desc')
            ->first()->balance??0;
        $total_wait = PayOutRequest::where('account_id',$account->id)->where('created_at','>',now()->subDay())->sum('amount');
        $total = $calc_amount - $total_wait;
        $avaible = min(get_wallet($account->id)-$total_wait,$total);
        $avaible = $avaible<0?0:$avaible;
        $text .="\n". "میزان برداشت ۲۴ ساعت گذشته : ".number_format($total_wait)." تومان";
        $text .="\n موجودی ۲۴ ساعت گذشته : ".number_format($calc_amount)." تومان";
        $text .="\n". "میزان برداشت قابل انجام : ".number_format($avaible)." تومان";
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$text
        ]);
    }
}
