<?php

namespace App\Console\Commands;

use App\Jobs\SendMessageJob;
use App\Models\Sponser;
use App\Models\SponserLink;
use Illuminate\Console\Command;

class CalcSponsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sponsers:calc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $active_sponsers=Sponser::where('status',1)->get();
        foreach ($active_sponsers as $sponser){
            $links = SponserLink::where('sponser_id',$sponser->id)->get();
            foreach ($links as $link){
                $usage = get_invite_link_state($sponser->username,$link->link);
                $link->update([
                    'usage'=>$usage
                ]);
                $remain = $usage-$link->calc;
                if ($remain>0){
                    $amount = $remain*$sponser->amount;
                    add_wallet($link->channel->account_id,$amount,"محاسبه $remain ممبر به نرخ $sponser->amount ",$sponser->id);
                }
                $link->calc = $usage;
                $link->save();
            }
            if ($sponser->limit !=-1){
                $sum_usage = $links->sum('usage');
                if ($sum_usage>=$sponser->limit){
                    $sponser->status = 0;
                    foreach ($links as $link){
                        SendMessageJob::dispatch([
                           'chat_id'=>$link->channel->account->chat_id,
                           'text'=>"ادمین محترم ، تبلیغات  $sponser->name به اتمام رسیده است"
                        ]);
                    }
                    $sponser->save();
                }
            }
        }
        return Command::SUCCESS;
    }
}
