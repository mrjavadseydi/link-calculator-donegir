<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\Channel;
use App\Models\SponserLink;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class CancelSponserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $sponser, $chat_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sponser, $chat_id)
    {
        $this->sponser = $sponser;
        $this->chat_id = $chat_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $sponser = $this->sponser;
        $link = SponserLink::where('sponser_id', $sponser->id)->get('channel_id');
        $accounts = Channel::query()->whereIn('id', $link)->get('account_id');
        foreach ($accounts as $account) {
            $arr = [
                'chat_id' => Account::find($account->account_id)->chat_id,
                'text' => "تبلیغ  $sponser->name  به اتمام رسید "
            ];
            SendMessageJob::dispatch($arr);
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"پیام همگانی در صف قرار گرفت "
        ]);
        $links = SponserLink::query()->where('sponser_id', $sponser->id)->get();
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"حذف لینک ها در حال انجام"
        ]);
        foreach ($links as $link) {
            revoke_link($sponser->username, $link->link);
        }
        sendMessage([
            'chat_id'=>$this->chat_id,
            'text'=>"لینک ها همگی غیرفعال شد !در حال حسابرسی ..."
        ]);
        Artisan::call('sponsers:calc');
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => "حسابرسی شد!"
        ]);
        $str = "تبلیغ : $sponser->name\n\n";
        $link_count = count($links);
        $all_usage = $links->sum('usage');
        $money = number_format($links->sum('calc') * $sponser->amount);
        $amount = number_format($sponser->amount) . " تومان ";
        $str .= "🔴 تعداد لینک های ساخته شده : $link_count

☑️تعداد ورود به لینک : $all_usage

💶 مبلغ هر ورود : $amount

💸 جمع مبلغ پرداختی : $money   تومان";
        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => $str
        ]);


        sendMessage([
            'chat_id' => $this->chat_id,
            'text' => "تبلیغ  $sponser->name لغو شد"
        ]);
        $sponser->update([
            'status' => 0
        ]);

    }
}
