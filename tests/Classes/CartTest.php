<?php

namespace App\Tests\Classe;

use App\Classe\Cart;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Request;

class CartTest extends TestCase {
    private $cart;

    protected function setUp(): void {
        // Mocking EntityManagerInterface
        $entityManager = $this->createMock(EntityManagerInterface::class);

        // Creating a real session with a mock storage to avoid side effects
        $session = new Session(new MockArraySessionStorage());

        // Using real RequestStack and pushing a request with our session
        $requestStack = new RequestStack();
        $request = Request::create('/'); // Create a new request
        $request->setSession($session); // Set our session to the request
        $requestStack->push($request); // Push the request to the RequestStack

        // Instantiating Cart with dependencies
        $this->cart = new Cart($requestStack, $entityManager);
    }

    public function testAdd() {
        $id = 1;

        $this->cart->add($id);

        // Assuming getSession will return a non-empty cart after adding a product
        $this->assertNotEmpty($this->cart->get());
    }

    public function testGet() {
        $this->cart->add(1);
        $this->cart->add(2);

        $cart = $this->cart->get();

        // Assuming that the cart has 2 products
        $this->assertCount(2, $cart);
    }

    public function testDelete() {
        $this->cart->add(1);
        $this->cart->delete(1);

        // Assuming the cart is empty after deleting the product
        $this->assertEmpty($this->cart->get());
    }
}
