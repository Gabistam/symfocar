<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/commande', name: 'app_order')]
    public function index(Cart $cart, Request $request): Response
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

    #[Route('/commande/recapitulatif', name: 'app_order_recap', methods: ['POST'])]
public function add(Cart $cart, Request $request): Response
{
    $form = $this->createForm(OrderType::class, null, [
        'user' => $this->getUser()
    ]);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $date = new \DateTimeImmutable();
        $carrier = $form->get('carrier')->getData();
        $delivery = $form->get('addresses')->getData();
        $delivery_content = $delivery->getFirstname().' '.$delivery->getLastname();
        if ($delivery->getCompany()) {
            $delivery_content .= '<br/>'.$delivery->getCompany();
        }
        $delivery_content .= '<br/>'.$delivery->getAdresse();
        $delivery_content .= '<br/>'.$delivery->getCP().' '.$delivery->getVille();
        $delivery_content .= '<br/>'.$delivery->getPays();


        //enregistrer ma commande Order()
        $order= new Order();
        $reference = $date->format('dmY').'-'.uniqid();
        $order->setReference($reference);
        $order->setUser($this->getUser());
        $order->setCreatedAt($date);
        $order->setCarrierName($carrier->getName());
        $order->setCarrierPrice($carrier->getPrice());
        $order->setDelivery($delivery_content);
        $order->setState(0);

        // Calculate and set totalTTC
        $totalTTC = ($order->getTotal() + $order->getCarrierPrice()) * 1.2;
        $order->setTotalTTC($totalTTC);

        $this->entityManager->persist($order);

        foreach ($cart->getFull() as $product) {
            $orderDetails = New OrderDetails();
            $orderDetails->setMyOrder($order);
            $orderDetails->setProduct($product['product']->getName());
            $orderDetails->setQuantity($product['quantity']);
            $orderDetails->setPrice($product['product']->getPrice());
            $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);
            $this->entityManager->persist($orderDetails);
        }

        $this->entityManager->flush();

        return $this->render('order/add.html.twig', 
        [
            'cart'=>$cart->getFull(),
            'carrier'=>$carrier,
            'delivery'=>$delivery_content,
            'reference'=>$order->getReference(),
            'totalTTC' => $order->getTotalTTC(),
        ]
        );
    }

    return $this->redirectToRoute('app_cart');
} 
 
}
