<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Entity\Adress;
use App\Entity\Order;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
    public function testGettersAndSetters() {
        $user = new User();
        
        $user->setEmail('test@example.com');
        $this->assertEquals('test@example.com', $user->getEmail());
        
        $user->setPassword('password123');
        $this->assertEquals('password123', $user->getPassword());
        
    }

    public function testFullName() {
        $user = new User();
        $user->setFirstname('John');
        $user->setLastname('Doe');

        $this->assertEquals('John Doe', $user->getFullName());
    }

    public function testAdressManagement() {
        $user = new User();
        $adress = new Adress();

        $user->addAdress($adress);
        $this->assertCount(1, $user->getAdresses());
        $this->assertEquals($user, $adress->getUser());

        $user->removeAdress($adress);
        $this->assertCount(0, $user->getAdresses());
    }

    public function testOrderManagement() {
        $user = new User();
        $order = new Order();

        $user->addOrder($order);
        $this->assertCount(1, $user->getOrders());
        $this->assertEquals($user, $order->getUser());

        $user->removeOrder($order);
        $this->assertCount(0, $user->getOrders());
    }
}
