<?php

namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart 
{
    private RequestStack $requestStack;
    private EntityManagerInterface $entityManager;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    {
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function add($id)
    {
        $cart = $this->requestStack->getSession() ->get('cart', []);
        
        if(!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->getSession()->set('cart', $cart);
    }

    public function get()
    {
        return $this->getSession()->get('cart');
    }

    public function remove()
    {
        return $this->getSession()->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->getSession()->get('cart', []);

        unset($cart[$id]);

        return $this->getSession()->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->getSession()->get('cart', []);

        if($cart[$id] > 1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->getSession()->set('cart', $cart);
    }

    public function getFull()
    {
        $cartComplete = [];

        if($this->get()) {
            foreach($this->get() as $id => $quantity) {
                $cartComplete[] = [
                    'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                    'quantity' => $quantity
                ];
            }
        }

        return $cartComplete;
    }

    public function getTotal()
    {
        $total = 0;

        foreach($this->getFull() as $item) {
            $total += $item['product']->getPrice() * $item['quantity'];
        }

        return $total;
    }

    private function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    
}

/**
* @Route("/panier", name="cart_")
*/