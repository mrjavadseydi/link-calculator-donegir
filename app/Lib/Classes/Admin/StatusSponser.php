<?php

namespace App\Lib\Classes\Admin;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\Channel;
use App\Models\Sponser;
use App\Models\SponserLink;

class StatusSponser extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "channel_post" && $this->chat_id == config('telegram.sponsers') && $this->text == "/detail");
    }

    public function handel()
    {

        $sponser = Sponser::query()->where('msg_id', $this->reply_to_message)->first();
        if (!$sponser) {
            return false;
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"دریافت شد ،در حال محاسبه"
        ]);
        $links = SponserLink::query()->where('sponser_id', $sponser->id)->orderBy('usage', 'desc')->get();
        $str = "تبلیغ : $sponser->name\n";
        foreach ($links as $link) {
            if (!Channel::find($link->channel_id)) {
                continue;
            }
            $usage = get_invite_link_state($sponser->username, $link->link);
            if (empty($usage)) {
                continue;
            }

            $channel = Channel::find($link->channel_id);
            $user_id = $channel->account->chat_id;
            $user_link = '<a href="tg://user?id=' . $user_id . '">' . $user_id . '</a>';
            $str .= "کاربر : $user_link\n";
            $str .= "نام کانال : " . $channel->name . "\n";
            $str .= "یوزر نیم کانال : " . $channel->username . "\n";
            $str .= "تعداد  : $link->usage \n";
            $str .= "دستور جایزه  : ";
            $str .= "/reward_$link->id" . '_mablagh' . "\n";
            if ($usage!=$link->usage){
               $str.="اختلاف : ".($usage-$link->usage)."\n";
            }
            $str .= "\n ======== \n";
            if (strlen($str) > 3000) {
                sendMessage([
                    'chat_id' => $this->chat_id,
                    'text' => $str,
                    'parse_mode' => 'HTML'
                ]);
                $str = "";
            }
        }
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => $str,
            'parse_mode' => 'HTML'
        ]);

    }
}
