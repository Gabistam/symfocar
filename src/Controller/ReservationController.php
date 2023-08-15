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
    #[Route('/api/reservation', name: 'api_reservation', methods: ['GET', 'POST'])]
    public function index(Request $request, EntityManagerInterface $entityManager, RentCarRepository $rentCarRepository): Response
    {
        if ($request->isMethod('GET')) {
            $availableCars = $rentCarRepository->findBy(['isDispo' => true]);
            return $this->json(['availableCars' => $availableCars]);
        }

        if ($request->isMethod('POST')) {
            $data = json_decode($request->getContent(), true);
            $reservation = new Reservation();
            
            $form = $this->createForm(ReservationType::class, $reservation);
            $form->submit($data);

            if ($form->isValid()) {
                $entityManager->persist($reservation);
                $entityManager->flush();
                return $this->json(['message' => 'Reservation successful!', 'reservationId' => $reservation->getId()], Response::HTTP_CREATED);
            } else {
                $errors = [];
                foreach ($form as $fieldName => $formField) {
                    foreach ($formField->getErrors(true) as $error) {
                        $errors[$fieldName][] = $error->getMessage();
                    }
                }
                return $this->json(['errors' => $errors], Response::HTTP_BAD_REQUEST);
            }
        }

        return $this->json(['message' => 'Method not allowed'], Response::HTTP_METHOD_NOT_ALLOWED);
    }
}
