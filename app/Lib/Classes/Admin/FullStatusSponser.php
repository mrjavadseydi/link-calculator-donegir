<?php
namespace App\Lib\Classes\Admin;
use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class FullStatusSponser extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="channel_post"&&$this->chat_id==config('telegram.sponsers')&&$this->text=="/status");
    }

    public function handel()
    {

        $sponser = Sponser::query()->where('msg_id',$this->reply_to_message)->first();
        if (!$sponser){
            return false;
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"دریافت شد ،در حال محاسبه"
        ]);
        $links = SponserLink::query()->where('sponser_id',$sponser->id)->get();
        $str = "تبلیغ : $sponser->name\n\n";
        $link_count = count($links);
        $all_usage = $links->sum('usage');
        $money = number_format($links->sum('calc') * $sponser->amount);
        $amount = number_format($sponser->amount)." تومان ";
        $total = 0;
//        foreach ($links as $link){
//            $usage = get_invite_link_state($sponser->username, $link->link);
//            if (empty($usage)) {
//                continue;
//            }else{
//                $total += $usage;
//            }
//        }
        $str.="🔴 تعداد لینک های ساخته شده : $link_count

☑️تعداد ورود به لینک : ".$links->sum('calc')."

💶 مبلغ هر ورود : $amount

💸 جمع مبلغ پرداختی : $money   تومان";
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$str
        ]);

    }
}
