<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class ExampleTest extends TestCase
{
    /**
     * 支払える金額を投入する
     */
    public function testPutMoneyCanPurchase()
    {
        $this->init();

        $this->putMoney(10);
        $this->assertEquals(10, $this->getAmount());
        $this->putMoney(50);
        $this->assertEquals(60, $this->getAmount());
        $this->putMoney(100);
        $this->assertEquals(160, $this->getAmount());
        $this->putMoney(500);
        $this->assertEquals(660, $this->getAmount());
        $this->putMoney(1000);
        $this->assertEquals(1660, $this->getAmount());
    }

    /**
     * 支払えない金額を投入する
     */
    public function testPutCanNotPurchase()
    {
        $this->init();

        $this->putMoney(1);
        $this->assertEquals(1, (int)Artisan::output());
        $this->putMoney(5000);
        $this->assertEquals(5000, (int)Artisan::output());
        $this->putMoney(10000);
        $this->assertEquals(10000, (int)Artisan::output());
    }

    /**
     * 投入金額の合計を取得する
     */
    public function testGetAmount()
    {
        $this->init();

        $this->putMoney(10);
        $this->putMoney(100);
        $this->assertEquals(110, $this->getAmount());
    }

    /**
     * 払い戻し後に釣り銭を出力する
     */
    public function testRefund()
    {
        $this->init();

        $this->putMoney(10);
        $this->putMoney(100);
        $this->resetMoney();
        $this->assertEquals(110, (int)Artisan::output());
    }

    /**
     * ジュースをセットする
     */
    public function testManageJuice()
    {
        $this->init();

        $juices = [
            'コーラ' => [
                'price' => 120,
                'quantity' => 5
            ]
        ];

        $this->assertEquals($this->getJuices()['コーラ']['price'], $juices['コーラ']['price']);
        $this->assertEquals($this->getJuices()['コーラ']['quantity'], $juices['コーラ']['quantity']);
    }

    /**
     * 購入可能であることを確認する
     */
    public function testCanPurchaseOK()
    {
        $this->init();

        $this->putMoney(100);
        $this->putMoney(10);
        $this->putMoney(10);
        $this->assertEquals(true, $this->canPurchase());
    }

    /**
     * 購入不可であることを確認する
     */
    public function testCanPurchaseNG()
    {
        $this->init();

        $this->putMoney(100);
        $this->assertEquals(false, $this->canPurchase());
    }

    /**
     * 投入金額が不足して購入出来ない
     */
    public function testPurchaseLackOfMoney()
    {
        $this->init();

        $quantity = Cache::get('juices')['コーラ']['quantity'];
        $this->putMoney(100);
        $this->purchaseJuice('コーラ');
        $this->assertEquals($quantity, $this->getJuices()['コーラ']['quantity']);
    }

    /**
     * 購入する(釣り銭あり)
     */
    public function testPurchaseCompleteHasChange()
    {
        $this->init();

        $quantity = $this->getJuices()['コーラ']['quantity'];
        $this->putMoney(100);
        $this->putMoney(10);
        $this->putMoney(10);
        $this->putMoney(10);
        $this->purchaseJuice('コーラ');
        $this->assertEquals($quantity - 1, $this->getJuices()['コーラ']['quantity']);
        $this->assertEquals(10, (int)Artisan::output());
    }

    /**
     * 購入する(釣り銭なし)
     */
    public function testPurchaseCompleteHasNotChange()
    {
        $this->init();

        $quantity = $this->getJuices()['コーラ']['quantity'];
        $this->putMoney(100);
        $this->putMoney(10);
        $this->putMoney(10);
        $this->purchaseJuice('コーラ');
        $this->assertEquals($quantity - 1, $this->getJuices()['コーラ']['quantity']);
        $this->assertEquals(0, (int)Artisan::output());
    }
}
