<?php

namespace App\Lib\Classes\Sponser;

use App\Lib\Interfaces\TelegramOprator;
use App\Models\Sponser;
use App\Models\SponserLink;

class GetBestSponserState extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type == "message" && $this->text == "❇️ نفرات برتر تبلیغات");
    }

    public function handel()
    {
        $text = "";
        $active_sponser = Sponser::where('status',1)->get();
        if (count($active_sponser) == 0) {
            return sendMessage([
                'chat_id' => $this->chat_id,
                'text' => "تبلیغ فعالی نیست ",
            ]);
        }
        $order = [
            'اول',
            'دوم',
            'سوم',
            'چهارم',
            'پنجم'
        ];
        foreach ($active_sponser as $sponser) {
            $tabligh = $sponser->name;
            $text .="🌀تبلیغ : $tabligh \n";
            $sponser_link = SponserLink::where('sponser_id',$sponser->id)
                ->orderBy('usage','desc')->limit(5)->get();

            foreach ($sponser_link as $i=>$spl){
                $or=$order[$i];
                $ac = $spl->channel->account;
                $name = $ac->account_name;
                $remain = strlen($ac->chat_id)-6;
                $id = substr($ac->chat_id,0,6);
                for($l=0;$l<$remain;$l++){
                    $id.="*";
                }
                $count = $spl->usage;
                $text .="✳️نفر $or :  $id  -   $name
💠تعداد : $count \n ";
            }
            $text.="\n〰️〰️〰️〰️\n";
        }
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => $text
        ]);
    }
}
