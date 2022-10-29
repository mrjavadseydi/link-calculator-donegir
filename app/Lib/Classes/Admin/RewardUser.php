<?php
namespace App\Lib\Classes\Admin;
use App\Jobs\SendMessageJob;
use App\Lib\Interfaces\TelegramOprator;
use App\Models\Account;
use App\Models\Channel;
use App\Models\Prize;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class RewardUser extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="channel_post"&&$this->chat_id==config('telegram.sponsers')&&strpos($this->text,'/reward_')!==false);
    }

    public function handel()
    {
        $text = explode('_',$this->text);
        $link_id = $text[1];
        $mablagh = $text[2];
        if (!is_numeric($mablagh)){
            return false;
        }
        $link = SponserLink::find($link_id);
        if (!$link){
            return false;
        }
        $channel = Channel::find($link->channel_id);
        $account = Account::find($channel->account_id);
        $spname = $link->sponser->name;
        $wallet  = add_wallet($account->id,$mablagh," جایزه از تبلیغ  $spname");
        Prize::query()->create([
            'account_id'=>$account->id,
            'sponser_id'=>$link->sponser_id,
            'wallet_id'=>$wallet->id,
            'amount'=>$mablagh,
        ]);

        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"جایزه به مبلغ $mablagh به کاربر $account->chat_id ارسال شد"
        ]);
        sendMessage([
            'chat_id'=>$account->chat_id,
            'text'=>"تبریک ! مبلغ $mablagh  به عنوان جایزه از تبلیغ $spname  به شما ارسال شد"
        ]);



    }
}
