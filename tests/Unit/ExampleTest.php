<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class ExampleTest extends TestCase
{
    /**
     * 支払える金額を投入する
     */
    public function testPutMoneyCanPurchase()
    {
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
        $this->putMoney(1);
        $this->assertEquals(1, Artisan::output());
        $this->putMoney(5000);
        $this->assertEquals(5000, Artisan::output());
        $this->putMoney(10000);
        $this->assertEquals(10000, Artisan::output());
    }

    /**
     * 投入金額の合計を取得する
     */
    public function testGetAmount()
    {
        $this->putMoney(10);
        $this->putMoney(100);
        $this->assertEquals(110, $this->getAmount());
    }

    /**
     * 払い戻し後に釣り銭を出力する
     */
    public function testRefund()
    {
        $this->putMoney(10);
        $this->putMoney(100);
        $this->resetMoney();
        $this->assertEquals(110, Artisan::output());
    }

    /**
     * ジュースをセットする
     */
    public function testManageJuice()
    {
        $this->setJuice();

        $juices = [
            'コーラ' => [
                'price' => 120,
                'quantity' => 5
            ]
        ];

        $this->assertEquals($this->getJuices()['price'], $juices['コーラ']['price']);
        $this->assertEquals($this->getJuices()['quantity'], $juices['コーラ']['quantity']);
    }

    /**
     * 購入可能であることを確認する
     */
    public function testCanPurchaseOK()
    {
        $this->setJuice();
        $this->putMoney(100);
        $this->putMoney(10);
        $this->putMoney(10);
        $this->assertEquals('ok', $this->canPurchase());
    }

    /**
     * 購入不可であることを確認する
     */
    public function testCanPurchaseNG()
    {
        $this->setJuice();
        $this->putMoney(100);
        $this->assertEquals('ng', $this->canPurchase());
    }

    /**
     * 投入金額が不足して購入出来ない
     */
    public function testPurchaseLackOfMoney()
    {
        $this->setJuice();
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
        $this->setJuice();
        $quantity = $this->getJuices()['コーラ']['quantity'];
        $this->putMoney(100);
        $this->putMoney(10);
        $this->putMoney(10);
        $this->putMoney(10);
        $this->purchaseJuice('コーラ');
        $this->assertEquals($quantity - 1, $this->getJuices()['コーラ']['quantity']);
        $this->assertEquals(10, Artisan::output());
    }

    /**
     * 購入する(釣り銭なし)
     */
    public function testPurchaseCompleteHasNotChange()
    {
        $this->setJuice();
        $quantity = $this->getJuices()['コーラ']['quantity'];
        $this->putMoney(100);
        $this->putMoney(10);
        $this->putMoney(10);
        $this->purchaseJuice('コーラ');
        $this->assertEquals($quantity - 1, $this->getJuices()['コーラ']['quantity']);
        $this->assertEquals(0, Artisan::output());
    }
}
