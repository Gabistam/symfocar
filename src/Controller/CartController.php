<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/cart', name: 'app_cart')]
    public function index(Cart $cart)
    {

        $cartComplete = [];
        $totalHT = 0;
        $totalTVA = 0;
        $totalTTC = 0;
        $totalQuantity = 0;

        if ($cart ->get() == null) {
            return $this->render('cart/index.html.twig', [
                'cart' => $cartComplete              
            ]);
        } else {
            foreach ($cart->get() as $id => $quantity) {
                $product = $this->entityManager->getRepository(Product::class)->findOneById($id);
                $productPrice = $product->getPrice();
                $totalHT += $quantity * $productPrice;
                $totalTVA += $quantity * $productPrice * 0.2;
                $totalTTC += $quantity * $productPrice * 1.2;
                $totalQuantity += $quantity;

                $cartComplete[] = [
                    'product' => $product,
                    'quantity' => $quantity
                ];
            }

            return $this->render('cart/index.html.twig', [
                'cart' => $cartComplete,
                'totalHT' => $totalHT,
                'totalTVA' => $totalTVA,
                'totalTTC' => $totalTTC,
                'totalQuantity' => $totalQuantity
            ]);
        }
    }

    #[Route('/cart/add/{id}', name: 'add_to_cart')]
    public function add(Cart $cart, $id): Response
    {
        $cart->add($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/remove', name: 'remove_cart')]
    public function remove(Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/delete/{id}', name: 'delete_to_cart')]
    public function delete(Cart $cart, $id): Response
    {
        $cart->delete($id);

        return $this->redirectToRoute('cart');
    }

    #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
    public function decrease(Cart $cart, $id): Response
    {
        $cart->decrease($id);

        return $this->redirectToRoute('cart');
    }
}
