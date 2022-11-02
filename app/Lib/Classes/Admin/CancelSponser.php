<?php
namespace App\Lib\Classes\Admin;
use App\Jobs\CancelSponserJob;
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

        CancelSponserJob::dispatch($sponser,$this->chat_id);


    }
}
