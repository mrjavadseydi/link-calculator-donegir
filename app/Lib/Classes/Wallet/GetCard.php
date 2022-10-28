<?php
namespace App\Lib\Classes\Wallet;
use App\Lib\Interfaces\TelegramOprator;
use Illuminate\Support\Facades\Cache;

class GetCard extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="message"&&get_state($this->chat_id)=="get_card");
    }

    public function handel()
    {
        if (strlen($this->text)!=16){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"شماره کارت وارد شده صحیح نمی باشد",
                'reply_markup'=>recive_wallet()
            ]);
            return;
        }else{
            $this->user->card = $this->text;
            $this->user->save();
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"شماره کارت با موفقیت ثبت شد, لطفا نام صاحب کارت را وارد کنید",
                'reply_markup'=>recive_wallet()
            ]);
            set_state($this->chat_id,"get_card_name");
        }
    }
}
