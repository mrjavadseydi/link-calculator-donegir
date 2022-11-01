<?php
namespace App\Lib\Classes\Admin;
use App\Jobs\RevokeLinksJob;
use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class CancelSponser extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="channel_post"&&$this->chat_id==config('telegram.sponsers')&&$this->text=="/cancel");
    }

    public function handel()
    {

        $sponser = Sponser::query()->where('msg_id',$this->reply_to_message)->where('status',1)->first();
        if (!$sponser){
            return false;
        }
        Artisan::call('sponsers:calc');
        $links = SponserLink::query()->where('sponser_id',$sponser->id)->get();
        foreach ($links as $link){
            RevokeLinksJob::dispatch($sponser->username,$link->link);
        }
        $str = "تبلیغ : $sponser->name\n\n";
        $link_count = count($links);
        $all_usage = $links->sum('usage');
        $money = number_format($links->sum('calc') * $sponser->amount);
        $amount = number_format($sponser->amount)." تومان ";
        $str.="🔴 تعداد لینک های ساخته شده : $link_count

☑️تعداد ورود به لینک : $all_usage

💶 مبلغ هر ورود : $amount

💸 جمع مبلغ پرداختی : $money   تومان";
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>$str
        ]);

        $link = SponserLink::where('sponser_id',$sponser->id)->get('channel_id');
        $accounts = Channel::query()->whereIn('id',$link)->get('account_id');
        foreach ($accounts as $account){
            $arr = [
                'chat_id'=>Account::find($account->account_id)->chat_id,
                'text'=>"تبلیغ  $sponser->name  به اتمام رسید "
            ];
            SendMessageJob::dispatch($arr);
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"تبلیغ  $sponser->name لغو شد"
        ]);
        $sponser->update([
            'status'=>0
        ]);


    }
}
