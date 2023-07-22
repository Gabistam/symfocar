<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderSuccessController extends AbstractController
{
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/commande/merci/{stripeSessionId}', name: 'app_order_validate')]
    public function index($stripeSessionId): Response
    {

        $order = $this->entityManagerInterface->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if (!$order->isIsPaid()) {
            // Envoyer un email à notre client pour lui confirmer sa commande
            // $mail = new Mail();
            // $content = "Bonjour " . $order->getUser()->getFirstname() . "<br/>Merci pour votre commande.<br/><br/>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, voluptatum. Quisquam, voluptatum.";
            // $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande La Boutique Française est bien validée.', $content);

            $order->isIsPaid(1);
            $this->entityManagerInterface->flush();
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
