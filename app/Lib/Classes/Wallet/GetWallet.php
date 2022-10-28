<?php

namespace App\Lib\Classes\Wallet;

use App\Lib\Interfaces\TelegramOprator;

class GetWallet extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == '💰کیف پول من');
    }

    public function handel()
    {
        if ($this->user->name == null) {
            set_state($this->chat_id,'add_card');
            sendMessage([
                'chat_id' => $this->chat_id,
                'text' => "لطفا شماره کارت ۱۶ رقمی خود را ارسال کنید",
                'reply_markup' => backKey()
            ]);
        } else {

            $amount = get_wallet($this->user->id);
            $text = str_replace('%amount', number_format($amount), config('robot.wallet_amount'));
            sendMessage([
                'chat_id' => $this->chat_id,
                'text' => $text,
                'reply_markup' => recive_wallet()
            ]);
        }

    }
}
