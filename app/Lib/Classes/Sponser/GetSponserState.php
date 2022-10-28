<?php
namespace App\Lib\Classes\Sponser;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Sponser;
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
        $active_sponser =
            SponserLink::whereIn('channel_id',$this->user->channels->pluck('id'))
                ->whereIn('sponser_id',Sponser::where('status',1)->pluck('id'))
                ->get();


        foreach ($active_sponser as $sponser){
            $text .= "کانال : ";
            $text.=$sponser->channel->name."\n";
                $text.="  لینک : $sponser->link \n ";
                $usage = get_invite_link_state($sponser->sponser->username,$sponser->link);
                $sponser->update([
                    'usage'=>$usage
                ]);

                $text .= "تعداد ورود به لینک : $usage \n";
                $text .= "تعداد ورود پرداخت شده :  $sponser->calc
                 \n";
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$text
        ]);
    }
}
