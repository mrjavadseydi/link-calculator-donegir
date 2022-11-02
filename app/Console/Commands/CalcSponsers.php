<?php

namespace App\Console\Commands;

use App\Jobs\AddWallet;
use App\Jobs\RevokeLinksJob;
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
        $active_sponsers = Sponser::where('status', 1)->get();
        foreach ($active_sponsers as $sponser) {
            $links = SponserLink::where('sponser_id', $sponser->id)->get();
            foreach ($links as $link) {
                if ($link->channel==null){
                    devLog($link);
                    continue;
                }
                $usage = get_invite_link_state($sponser->username, $link->link);
                if (empty($usage)) {
                    $usage = 0;
                }
                $link->update([
                    'usage' => $usage
                ]);
                $remain = $usage - $link->calc;

                $amount = $remain * $sponser->amount;
                if ($amount!=0){
                    AddWallet::dispatch($link->channel->account_id, $amount, "محاسبه $remain ممبر به نرخ $sponser->amount ", $sponser->id);
//                    add_wallet();

                }
                $link->calc = $usage;
                $link->save();
            }
            if ($sponser->limit != -1) {
                sendMessage([
                    'chat_id'=>config('telegram.sponsers'),
                    'text'=>"تبلیغ $sponser->name به محدودیت $sponser->limit رسیده است"
                ]);

                $sum_usage = $links->sum('usage');
                if ($sum_usage >= ($sponser->limit-150)) {
                    foreach ($links as $link){
                        RevokeLinksJob::dispatch($sponser->username,$link->link);
                    }
                    $sponser->status = 0;
                    foreach ($links as $link) {
                        SendMessageJob::dispatch([
                            'chat_id' => $link->channel->account->chat_id,
                            'text' => "ادمین محترم ، تبلیغات  $sponser->name به اتمام رسیده است"
                        ]);
                    }
                    $sponser->save();
                }
            }
        }
        return Command::SUCCESS;
    }
}
