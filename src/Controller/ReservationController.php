<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\RentCarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request, EntityManagerInterface $entityManager, RentCarRepository $rentCarRepository): Response
    {
        $availableCars = $rentCarRepository->findBy(['isDispo' => true]);

        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reservation);
            $entityManager->flush();

            // Ajouter une redirection après la soumission réussie du formulaire
            // par exemple vers une page de remerciement ou la liste des réservations.
            return $this->redirectToRoute('app_account');
        }

        return $this->render('reservation/index.html.twig', [
            'form' => $form->createView(),
            'availableCars' => $availableCars
        ]);
    }
}
