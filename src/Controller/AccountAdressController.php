<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Adress;
use App\Form\AdressType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountAdressController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/account/adresses', name: 'app_account_adress')]
    public function index(): Response
    {
        return $this->render('account/adress.html.twig');
    }

    #[Route('/account/adresses/add', name: 'app_account_adress_add')]
    public function add(Cart $cart, Request $request): Response
    {
        $adress = new Adress();
        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $adress->setUser($this->getUser());
            $this->entityManager->persist($adress);
            $this->entityManager->flush();

            if ($cart->get()) {
                return $this->redirectToRoute('app_order');
            }else {
                return $this->redirectToRoute('app_account_adress');
            }
        }

        return $this->render('account/adress_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/adresses/edit/{id}', name: 'app_account_adress_edit')]
    public function edit(Request $request, $id): Response
    {
        $adress = $this->entityManager->getRepository(Adress::class)->findOneById($id);

        if (!$adress || $adress->getUser() != $this->getUser()) {
            return $this->redirectToRoute('app_account_adress');
        }

        $form = $this->createForm(AdressType::class, $adress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_account_adress');
        }

        return $this->render('account/adress_form.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/account/adresses/delete/{id}', name: 'app_account_adress_delete')]
    public function delete($id): Response
    {
        $adress = $this->entityManager->getRepository(Adress::class)->findOneById($id);

        if ($adress && $adress->getUser() == $this->getUser()) {
            $this->entityManager->remove($adress);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_account_adress');
    }
}
