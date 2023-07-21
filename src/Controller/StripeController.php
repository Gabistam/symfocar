<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session', name: 'app_stripe_create_session', methods: ['POST'])]
    public function index(EntityManagerInterface $entityManagerInterface,Cart $cart, Request $request): Response
    {
        $reference = $request->get('reference');

        $YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $products_for_stripe = [];

        $order = $entityManagerInterface->getRepository(Order::class)->findOneByReference($reference);

        if ($order === null) {
            // La commande n'a pas été trouvée, retourner une erreur ou rediriger l'utilisateur
            throw $this->createNotFoundException('La commande n\'a pas été trouvée.');
        }

        foreach ($cart->getFull() as $product) {
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => $_ENV['STRIPE_CURRENCY'],
                    'unit_amount' => round($product['product']->getPrice() ),
                    'product_data' => [
                        'name' => $product['product']->getName(),

                        //ne fonctionne pas en local
                        // 'images' => [$YOUR_DOMAIN . "/assets/images/uploads/" . $product['product']->getIllustration()],
                    ],
                ],
                'quantity' => $product['quantity'],
            ];
        }

        $products_for_stripe[] = [
            'price_data' => [
                'currency' => $_ENV['STRIPE_CURRENCY'],
                'unit_amount' => $order->getCarrierPrice() * 100,
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        $totalPrice = 0;
        foreach ($cart->getFull() as $product) {
            $totalPrice += $product['product']->getPrice() * $product['quantity'];
        }

        $TVA = ($totalPrice + $order->getCarrierPrice()) * 0.2;

        $products_for_stripe[] = [
            'price_data' => [
                'currency' => $_ENV['STRIPE_CURRENCY'],
                'unit_amount' => round($TVA ),
                'product_data' => [
                    'name' => 'TVA',
                    // 'images' => [$YOUR_DOMAIN . "/assets/images/uploads/" . $product['product']->getIllustration()],
                ],
            ],
            'quantity' => 1,
        ];


        Stripe::setApiKey($_ENV['STRIPE_SECRET_KEY']);
            

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                $products_for_stripe
            ],
            'mode' => 'payment',
            'success_url' => $YOUR_DOMAIN . '/success.html',
            'cancel_url' => $YOUR_DOMAIN . '/cancel.html',
        ]);

        return $this->redirect($checkout_session->url, 303);

        
    }
}
