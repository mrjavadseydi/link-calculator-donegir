<?php
namespace App\Lib\Classes\Wallet;
use App\Lib\Interfaces\TelegramOprator;
use Illuminate\Support\Facades\Cache;

class GetShaba extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="message"&&get_state($this->chat_id)=="get_shaba");
    }

    public function handel()
    {
        if (strlen($this->text)!=26){
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"شماره شبا وارد شده صحیح نمی باشد",
                'reply_markup'=>backKey()
            ]);
            return;
        }else{
            $this->user->shaba = $this->text;
            $this->user->save();
            sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"شماره شبا با موفقیت ثبت شد",
            ]);
//            $amount = get_wallet($this->user->id);
//            $text = str_replace('%amount', number_format($amount), config('robot.wallet_amount'));
//            sendMessage([
//                'chat_id' => $this->chat_id,
//                'text' => $text,
//                'reply_markup' => recive_wallet()
//            ]);
        }
    }
}
