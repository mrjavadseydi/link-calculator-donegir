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

class CancelSponser extends TelegramOprator
{

    public function initCheck()
    {

        return ($this->message_type=="channel_post"&&$this->chat_id==config('telegram.sponsers')&&$this->text=="/cancel");
    }

    public function handel()
    {

        $sponser = Sponser::query()->where('msg_id',$this->reply_to_message)->first();
        if (!$sponser){
            return false;
        }
        Artisan::call('sponsers:calc');
        $link = SponserLink::where('sponser_id',$sponser->id)->get('channel_id');
        $accounts = Channel::query()->whereIn('id',$link)->get('account_id');
        foreach ($accounts as $account){
            $arr = [
                'chat_id'=>Account::find($account->account_id)->chat_id,
                'text'=>"تبلیغ  $sponser->name لغو شد"
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
