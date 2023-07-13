<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;

class AccountPasswordController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    #[Route('/account/update-password', name: 'app_accountPassword')]
    public function index(Request $request, UserPasswordHasherInterface $hasher, UserInterface $getUserIdentifier): Response
    {
        $notification = null;

        // $user = $this->getUser();
        $user = $this->entityManager->getRepository(User::class)->findOneByEmail($getUserIdentifier->getUserIdentifier());
        
        $form = $this->createForm(ChangePasswordType::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();


            if ($hasher->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $password = $hasher->hashPassword($user, $new_pwd);

                $user->setPassword($password);
                $this->entityManager->persist($user);
                $this->entityManager->flush($user);
                $notification = 'Votre mot de passe a bien été mise à jour';
            } else {
                $notification = 'Votre mot de passe actuel est incorrect';
            }

        }

        return $this->render('account/accountPassword.html.twig', [
            'updatePasswordForm' => $form->createView(),
            'notification' => $notification,
        ]);
    }
}
