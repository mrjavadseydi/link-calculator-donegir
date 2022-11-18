<?php

namespace App\Lib\Classes\Sponser;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\Sponser;
use App\Models\SponserLink;

class GetSponserState extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == "🕹 جزییات تبلیغات");
    }

    public function handel()
    {
        $text = "لطفا انتتخاب کنید ";
//        $active_sponser =
//            SponserLink::whereIn('channel_id', $this->user->channels->pluck('id'))
//                ->whereIn('sponser_id', Sponser::where('status', 1)->pluck('id'))
//                ->get();
//
//        if (count($active_sponser) == 0) {
//            return sendMessage([
//                'chat_id' => $this->chat_id,
//                'text' => "شما هیچ تبلیغی فعال ندارید",
//            ]);
//        }
//        foreach ($active_sponser as $sponser) {
//            $id = $sponser->sponser_id;
//            $tabligh = $sponser->sponser->name;
//            $calc = $sponser->calc;
//            $channel = $sponser->channel->name;
//            $link= $sponser->link;
////            $usage = get_invite_link_state($sponser->sponser->username, $sponser->link);
////            $sponser->update([
////                'usage' => $usage
////            ]);
//
//            $text.="🔶شناسه تبلیغ : #$id
//🔊نام تبلیغ : $tabligh
//🔴تعداد ورود : $calc
//⚜️کانال شما : $channel
//🔗لینک شما : $link
//〰️〰️〰️〰️\n";
//        }
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => $text,
            'reply_markup'=>sponser_state_menu()
        ]);
    }
}
