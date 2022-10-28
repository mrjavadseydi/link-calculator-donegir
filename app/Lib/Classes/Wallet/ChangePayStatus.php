<?php
namespace App\Lib\Classes\Wallet;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\PayOutRequest;
use Illuminate\Support\Facades\Cache;

class ChangePayStatus extends TelegramOprator
{

    public function initCheck()
    {

        if ($this->message_type == "callback_query") {
            $ex = explode("_", $this->data);
            if ($ex[0] == "status") {
                return true;
            }
        }
        return false;
    }

    public function handel()
    {
        $ex = explode("_", $this->data);
        if ($ex[1]==0){
            editMessageText([
                'chat_id'=>$this->chat_id,
                'message_id'=>$this->message_id,
                'text'=>$this->text."\n در انتظار بررسی ",
                'reply_markup'=>payoutMenu()
            ]);
        }elseif ($ex[1]==2){
            editMessageText([
                'chat_id'=>$this->chat_id,
                'message_id'=>$this->message_id,
                'text'=>$this->text."\n رد شد "
            ]);
        }else{
            editMessageText([
                'chat_id'=>$this->chat_id,
                'message_id'=>$this->message_id,
                'text'=>$this->text."\n تایید شد "
            ]);
            $payout = PayOutRequest::where('msg_id',$this->message_id)->first();
            $user =$payout->wallet->account ;
            sendMessage([
                'chat_id'=>$user->chat_id,
                'text'=>"درخواست شما ($payout->id) تایید شد و  به حساب شما واریز شد"
            ]);


        }

    }
}
