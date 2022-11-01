<?php
namespace App\Lib\Classes\Account;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Cache;

class GetSponserHistory extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="message"&&$this->text=="🔸سابقه تبلیغات🔸");
    }

    public function handel()
    {
        $text = "";
        $active_sponser =
            SponserLink::whereIn('channel_id',$this->user->channels->pluck('id'))
                ->whereIn('sponser_id',Sponser::where('status',0)->pluck('id'))
                ->get();

        if (count($active_sponser)==0){
            return sendMessage([
                'chat_id'=>$this->chat_id,
                'text'=>"شما هیچ تبلیغی  ندارید",
            ]);
        }

        foreach ($active_sponser as $sponser){
            $usage = $sponser->usage;
            $id = $sponser->sponser_id;
            $tabligh = $sponser->sponser->name;
            $text.="🔊تبلیغ : $tabligh
👤میزان جذب شما : $usage
🔆کد تبلیغ: #$id\n〰️〰️〰️\n";
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$text
        ]);
    }
}
