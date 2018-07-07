<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Cache;
use Carbon\Carbon;

class PutMoney extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issue:put {money}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $money = $this->argument('money');
        Cache::increment('amount', $money);
    }
}
