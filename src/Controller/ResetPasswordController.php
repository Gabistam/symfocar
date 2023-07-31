<?php

namespace App\Controller;

use DateTime;
use App\Classe\Mail;
use App\Entity\User;
use App\Entity\ResetPassword;
use App\Form\ResetPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/reset/password', name: 'app_resetPassword')]
    public function index(Request $request): Response
    {
        if($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        if($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));

            if($user) {
                // 1) Generate token
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt(new \DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();


                // 2) Send email
                $url = $this->generateUrl('app_updatePassword', [
                    'token' => $reset_password->getToken()
                ]);

                $content = "Bonjour ".$user->getFirstname().", <br/><br/><br/>Vous avez demandé à réinitialiser votre mot de passe sur le site Symfocar.<br/><br/>";
                $content .= "Merci de bien vouloir cliquer sur le lien suivant pour <a rel='nofollow noopener noreferrer' href='".$url."' style='color:blue;font-weight:bold;'>mettre à jour votre mot de passe</a>.";

                $mail = new Mail();
                $mail->send($user->getEmail(), $user->getFirstname().' '.$user->getLastname(), 'Réinitialiser votre mot de passe sur Symfocar', $content);

                $this->addFlash('notice', 'Vous allez recevoir dans quelques secondes un email avec la procédure pour réinitialiser votre mot de passe.');
            } else {
                $this->addFlash('notice', 'Cette adresse email est inconnue.');
            }

        }


        return $this->render('reset_password/index.html.twig');
    }

    #[Route('/update-password/{token}', name: 'app_updatePassword')]
    public function updatePassword($token, Request $request, UserPasswordHasherInterface $hasher): Response
    {       
        $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);

        if(!$reset_password) {
            return $this->redirectToRoute('app_resetPassword');
        }

        // Vérifier si le createdAt = now - 3h
        $now = new DateTime();
        if($now > $reset_password->getCreatedAt()->modify('+ 3 hour')) {
            $this->addFlash('notice', 'Votre demande de mot de passe a expiré. Merci de la renouveler.');
            return $this->redirectToRoute('app_resetPassword');
        }

        // Rendre une vue avec mot de passe et confirmer mot de passe
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $new_password = $form->get('new_password')->getData();

            // Hashage du mot de passe
            $password = $hasher->hashPassword($reset_password->getUser(), $new_password);
            $reset_password->getUser()->setPassword($password);
            
            

            // Flush en base de données
            $this->entityManager->flush();

            // Redirection de l'utilisateur vers la page de connexion
            $this->addFlash('notice', 'Votre mot de passe a bien été mis à jour.');
            return $this->redirectToRoute('app_login');
            
        }

        return $this->render('reset_password/update.html.twig', [
            'form' => $form->createView()
        ]);
        
    }

            
}
