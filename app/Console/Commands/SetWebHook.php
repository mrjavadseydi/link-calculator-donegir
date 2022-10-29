<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SetWebHook extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:hook {--url=}';

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
        $url = $this->option('url');
        $token = config('telegram.bots.mybot.token');

        $response = file_get_contents("https://api.telegram.org/bot{$token}/setWebhook?url=$url/telegram");
        $this->info($response);
        return Command::SUCCESS;
    }
}
