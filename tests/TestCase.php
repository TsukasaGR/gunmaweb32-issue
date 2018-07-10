<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function init()
    {
        $this->resetMoney();
        $this->setJuice();
    }

    protected function setJuice()
    {
        Artisan::call('issue:set');
    }

    protected function putMoney(int $money)
    {
        Artisan::call('issue:put', [
            'money' => $money,
        ]);
    }

    protected function purchaseJuice(string $juice)
    {
        Artisan::call('issue:purchase', [
            'juice' => $juice,
        ]);
    }

    protected function resetMoney()
    {
        Artisan::call('issue:reset');
    }

    protected function canPurchase()
    {
        Artisan::call('issue:canPurchase');
        return (boolean)Artisan::output();
    }

    protected function getAmount()
    {
        return Cache::get('amount');
    }

    protected function getJuices()
    {
        return Cache::get('juices');
    }}
