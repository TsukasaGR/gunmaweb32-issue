<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Cache;
use Carbon\Carbon;

class PurchaseJuice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issue:purchase {juice}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'お題';

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
        $juice = $this->argument('juice');
        $juices = Cache::get('juices');

        if (Cache::get('amount') < $juices['コーラ']['price']) {
            exit;
        }

        // おつりを出力
        echo Cache::get('amount');
    }
}
