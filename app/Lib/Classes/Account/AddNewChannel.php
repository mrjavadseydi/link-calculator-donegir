<?php

namespace App\Lib\Classes\Account;

use App\Lib\Interfaces\TelegramOprator;

class ChangeBankInfo extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == '🔸تغییر اطلاعات بانکی🔸');
    }

    public function handel()
    {
        set_state($this->chat_id,'add_card');
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => "لطفا شماره کارت ۱۶ رقمی خود را ارسال کنید",
            'reply_markup' => backKey()
        ]);
    }
}
