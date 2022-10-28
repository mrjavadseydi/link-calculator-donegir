<?php
namespace App\Lib\Classes\Sponser;
use App\Lib\Interfaces\TelegramOprator;
use Illuminate\Support\Facades\Cache;

class ChoseSponser extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="message"&&$this->text=="🔴دریافت تبلیغ");
    }

    public function handel()
    {
        set_state($this->chat_id,"get_sponser");
        Cache::forget('active_select' . $this->user->id);
        $text= "";
        foreach (\App\Models\Sponser::where('status',1)->get() as $channel) {
            $text .= "
        ▫️ نام تبلیغ: $channel->name
▫️ قیمت برای هر نفر دعوت: ".number_format($channel->amount)."
▫️ کد تبلیغ: #$channel->id
▫️ توضیحات:
$channel->description
        ";
            if ($channel->limit != -1) {
                $text .= "محدودیت : $channel->limit";
                }
            $text .= "\n";
        }

        sendMessage([
            'chat_id' => $this->chat_id,
            'text'=>$text,
            'reply_markup'=>choose_sponser()
        ]);
    }
}
