<?php
namespace App\Lib\Classes\Wallet;
use App\Lib\Interfaces\TelegramOprator;
use Illuminate\Support\Facades\Cache;

class GetCardName extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="message"&&get_state($this->chat_id)=="get_card_name");
    }

    public function handel()
    {
        $this->user->name = $this->text;
        $this->user->save();
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"نام صاحب کارت با موفقیت ثبت شد, لطفا شماره شبا حساب خود را وارد کنید",
            'reply_markup'=>recive_wallet()
        ]);
        set_state($this->chat_id,"get_shaba");
    }
}
