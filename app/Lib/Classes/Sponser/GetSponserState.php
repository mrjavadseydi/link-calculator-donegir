<?php
namespace App\Lib\Classes\Sponser;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Cache;

class GetSponserState extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="message"&&$this->text=="🕹 جزییات تبلیغات");
    }

    public function handel()
    {
        $text = "";
        foreach ($this->user->channels as $channel){
            $text .= "کانال : ";

            $text.=$channel->name."\n";
            foreach (SponserLink::where('channel_id',$channel->id)->get() as $sponser){
                $text.="  لینک : $sponser->link \n ";
                $usage = get_invite_link_state($sponser->sponser->username,$sponser->link);
                $sponser->update([
                    'usage'=>$usage
                ]);
                $text .= "تعداد ورود به لینک : $usage \n";
            }
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$text
        ]);
    }
}
