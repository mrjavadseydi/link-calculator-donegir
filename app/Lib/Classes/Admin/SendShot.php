<?php

namespace App\Lib\Classes\Admin;

use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\PayOutRequest;
use App\Models\Sponser;

class SendShot extends TelegramOprator
{

    public function initCheck()
    {
        return ($this->message_type == "channel_photo" && $this->chat_id == config('telegram.sponsers') && isset($this->update['channel_post']['reply_to_message']));
    }

    public function handel()
    {
        $payout = PayOutRequest::where('msg_id',$this->update['channel_post']['reply_to_message']['message_id'])->first();
        if (!$payout){
            return false;
        }
        $photo = $this->update['channel_post']['photo'];
        $photo = end($photo);
        sendPhoto([
            'chat_id' => $payout->account->chat_id,
            'photo' => $photo['file_id'],
            'caption' => "به حساب شما پرداخت شد.✅ و حداکثر تا ۲۴ ساعت کاری به حساب شما واریز خواهد شد
از اعتماد شما صمیمانه سپاسگذاریم."
        ]);

    }
}
