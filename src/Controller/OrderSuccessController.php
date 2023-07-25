<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Classe\Mail;
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
    public function index(Cart $cart, $stripeSessionId): Response
    {

        $order = $this->entityManagerInterface->getRepository(Order::class)->findOneByStripeSessionId($stripeSessionId);

        if (!$order || $order->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if ($order->getState() == 0) {
            // Vider la session "cart"
            $cart->remove();

            // Envoyer un email à notre client pour lui confirmer sa commande
            $mail = new Mail();
            $content = "Bonjour " . $order->getUser()->getLastname() . "<br/><br/>Merci pour votre achat.<br/><br/><br/>Votre commande n° <b>". $order->getReference() . "</b> d'un montant de <b>" . number_format($order->getTotalTTC() / 100, 2, ',', ' ') . " €</b> est bien validée.<br/><br/>
            Vous pouvez consulter votre espace personnel pour obtenir plus de détails.<br/><br/><br/>A très bientôt sur Symfocar.<br/><br/><br/><br/><br/>L'équipe Symfocar.";
            $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande Symfocar est bien validée.', $content);

            $order->setState(1);
            $this->entityManagerInterface->flush();
        }

        return $this->render('order_success/index.html.twig', [
            'order' => $order,
        ]);
    }
}
