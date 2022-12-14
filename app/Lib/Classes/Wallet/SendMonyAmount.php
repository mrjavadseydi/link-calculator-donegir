<?php

namespace App\Lib\Classes\Wallet;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\PayOutRequest;
use App\Models\Wallet;

class SendMonyAmount extends TelegramOprator
{

    public function initCheck()
    {
        if ($this->message_type == "callback_query") {
            $ex = explode("_", $this->data);
            if ($ex[0] == "getwallet") {
                return true;
            }
        }
        return false;
    }

    public function handel()
    {
        deleteMessage([
            'chat_id' => $this->chat_id,
            'message_id' => $this->message_id
        ]);

        $calc_amount = Wallet::where('account_id', $this->user->id)->where('created_at', '<', now()->subDay())->orderBy('id', 'desc')
            ->first()->balance??0;
        $total_wait = PayOutRequest::where('account_id', $this->user->id)->where('created_at','>',now()->subDay())->sum('amount');
        $total = $calc_amount - $total_wait;
        $avaible = min(get_wallet($this->user->id)-$total_wait,$total);
        $avaible = $avaible<0?0:$avaible;
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => config('robot.receive_wallet'). "\n مبلغ قابل برداشت : " . number_format($avaible) . " تومان",
            'reply_markup' => backKey()
        ]);
        set_state($this->chat_id, 'receive_wallet');
    }
}
