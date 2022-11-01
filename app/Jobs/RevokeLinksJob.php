<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RevokeLinksJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $username;
    public $link;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($username,$link)
    {
        $this->username = $username;
        $this->link = $link;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        revoke_link($this->username,$this->link);
    }
}
