<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WalletDiffCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wallet:diff';

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
        $accounts = \App\Models\Account::all();
        foreach ($accounts as $account) {
            $wallet_now = get_wallet($account->id);
            $real_wallet = \App\Models\Wallet::where('account_id', $account->id)->sum('action');
            if ($real_wallet != $wallet_now) {
                \App\Models\Wallet::where('account_id',$account->id)->orderBy('id','desc')->first()->update([
                    'balance'=>$real_wallet
                ]);
                devLog([$account->id, $wallet_now, $real_wallet, $real_wallet - $wallet_now]);
//                $account_wallet = \App\Models\Wallet::where('account_id', $account->id)->pluck('id');
//                for ($i = 0; $i < count($account_wallet); $i++) {
//                    if (isset($account_wallet[$i + 1])) {
//                        $wallet0 = \App\Models\Wallet::find($account_wallet[$i]);
//                        $wallet1 = \App\Models\Wallet::find($account_wallet[$i + 1]);
//                        $sum = $wallet0->balance + $wallet1->action;
////                        if ($sum != $wallet1->balance) {
////                            devLog([$wallet0->id, $wallet1->id]);
////                        }
//                    }
//                }


            }
//
        }

    }

}
