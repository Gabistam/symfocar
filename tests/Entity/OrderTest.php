<?php

namespace App\Tests\Entity;

use App\Entity\Order;
use App\Entity\OrderDetails;
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    public function testGetTotal()
    {
        $order = new Order();

        $orderDetail1 = $this->createMock(OrderDetails::class);
        $orderDetail1->expects($this->any())->method('getPrice')->willReturn(50.0); // Notez le .0 pour faire de cela un float
        $orderDetail1->expects($this->any())->method('getQuantity')->willReturn(2);

        $orderDetail2 = $this->createMock(OrderDetails::class);
        $orderDetail2->expects($this->any())->method('getPrice')->willReturn(30.0); // De même ici
        $orderDetail2->expects($this->any())->method('getQuantity')->willReturn(1);

        $order->addOrderDetail($orderDetail1);
        $order->addOrderDetail($orderDetail2);

        $this->assertEquals(130, $order->getTotal());
    }

    public function testGetTotalTTC()
    {
        $order = new Order();
        $order->setCarrierPrice(10);

        $orderDetail = $this->createMock(OrderDetails::class);
        $orderDetail->method('getPrice')->willReturn(100.0);
        $orderDetail->method('getQuantity')->willReturn(1);

        $order->addOrderDetail($orderDetail);

        $this->assertEquals(132, $order->getTotalTTC()); // (100 + 10) * 1.2 = 132
    }

    public function testAddOrderDetail()
    {
        $order = new Order();
        $orderDetail = $this->createMock(OrderDetails::class);

        $order->addOrderDetail($orderDetail);

        $this->assertCount(1, $order->getOrderDetails());
    }

    public function testRemoveOrderDetail()
    {
        $order = new Order();
        $orderDetail = $this->createMock(OrderDetails::class);

        $order->addOrderDetail($orderDetail);
        $order->removeOrderDetail($orderDetail);

        $this->assertCount(0, $order->getOrderDetails());
    }
}
