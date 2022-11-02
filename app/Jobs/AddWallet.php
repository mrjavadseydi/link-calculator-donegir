<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddWallet implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $account_id;
    public $amount;
    public $description;
    public $sp_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($account_id,$amount,$description,$sp_id)
    {

        $this->account_id = $account_id;
        $this->amount = $amount;
        $this->description = $description;
        $this->sp_id = $sp_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         \App\Models\Wallet::query()->create([
            'balance'=>get_wallet($this->account_id)+$this->amount,
            'account_id'=>$this->account_id,
            'action'=>$this->amount,
            'description'=>$this->description,
            'sponser_id'=>$this->sp_id
        ]);
    }
}
