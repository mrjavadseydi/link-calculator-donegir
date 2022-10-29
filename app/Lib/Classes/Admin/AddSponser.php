<?php

namespace App\Lib\Classes\Admin;

use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Sponser;

class AddSponser extends TelegramOprator
{

    public function initCheck()
    {
        $ex = explode("\n", $this->text);
        return ($this->message_type == "channel_post" && $this->chat_id == config('telegram.sponsers') && count($ex) > 3);
    }

    public function handel()
    {

        $ex = explode("\n", $this->text);
        Sponser::query()->create([
            'name' => $ex[0],
            'username' => $ex[1],
            'description' => $ex[2],
            'amount' => $ex[3],
            'limit' => $ex[4],
            'msg_id' => $this->message_id
        ]);
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => "تبلیغ با موفقیت اضافه شد"
        ]);
        if (isset($ex[5])) {
            $channels = Channel::query()->groupBy('account_id')->get('account_id');

            foreach ($channels as $channel) {
                $price = number_format($ex[3]);
                $arr = [
                    'chat_id' => Account::find($channel->account_id)->chat_id,
                    'text' => "تبلیغ  جدید اضافه شد\n
                    نام : $ex[0]\n
                    توضیحات : $ex[2]\n
                    مبلغ :  $price \n
                    محدودیت : $ex[4]"
                ];
                SendMessageJob::dispatch($arr);
            }
        }

    }
}
