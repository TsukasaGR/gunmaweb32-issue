<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Exception;

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
    protected $description = 'ジュースを購入する';

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
        $amount = Cache::get('amount');
        $juices = Cache::get('juices');

        if (!$juices) {
            throw new Exception('not exists juices');
        }

        // 金額が足りていない場合は何もせず終了
        if (!$amount && $amount < $juices[$juice]['price']) {
            throw new Exception('not exists juices');
        }

        // 数量を1減らして釣り銭を計算する
        $juices[$juice]['quantity'] = $juices[$juice]['quantity'] - 1;
        $expiresAt = Carbon::now()->addMinutes(10);
        Cache::put('juices', $juices, $expiresAt);
        Cache::put('amount', $amount - $juices[$juice]['price'], $expiresAt);

        // おつりを出力
        echo Cache::get('amount');
    }
}
