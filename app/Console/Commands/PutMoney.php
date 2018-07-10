<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

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
        if ($this->validation()) {
            $money = $this->argument('money');
            Cache::increment('amount', $money);
        }
    }

    private function validation()
    {
        $invalidMoney = [1, 5000, 10000];
        $money = $this->argument('money');
        if (in_array($money, $invalidMoney)) {
            $this->info($money);
            return false;
        }

        return true;
    }
}
