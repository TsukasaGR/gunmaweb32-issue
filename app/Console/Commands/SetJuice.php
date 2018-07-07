<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use \Cache;
use Carbon\Carbon;

class SetJuice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'issue:set';

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
        Cache::forget('juices');

        $expiresAt = Carbon::now()->addMinutes(10);

        $juices = [
            'コーラ' => [
                'price' => 120,
                'quantity' => 5
            ]
        ];
        Cache::put('juices', $juices, $expiresAt);
    }
}
