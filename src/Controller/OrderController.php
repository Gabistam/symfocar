<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\OrderType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart): Response
    {
        /** @noinspection PhpUndefinedMethodInspection */
        if ($this->getUser()->getAdresses()->isEmpty()) {

            return $this->redirectToRoute('app_account_adress_add');
        }

        $form = $this->createForm(OrderType::class, null, [
            'user' => $this->getUser()
        ]);


        return $this->render('order/index.html.twig', 
        [
            'form' => $form->createView(),
            'cart'=>$cart->getFull(),
        ]
        );
    }
}
