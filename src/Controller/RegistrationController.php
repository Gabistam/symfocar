<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $notification = null;

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

                $search_mail = $entityManager->getRepository(User::class)->findOneByEmail($user->getEmail());

                if(!$search_mail){
                    $user->setPassword($userPasswordHasher->hashPassword($user, $form->get('plainPassword')->getData()));
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $mail = new Mail();
                    $content = "Bonjour ".$user->getLastname()."<br/><br/><br/>Bienvenue sur notre site dédié à la vente de véhicule.<br/><br/> <b>Votre compte a été créé avec succès.</b><br/><br/><br/>
                    Acceder à votre compte : <a href='http://127.0.0.1:8000/login'>https://symfocar.com/login</a><br/><br/><br/>A très bientôt sur Symfocar.<br/><br/><br/><br/><br/>L'équipe Symfocar.";
                    $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur symfocar', $content);

                    $notification = "Bonjour " . $user->getFirstname(). ", votre inscription s'est correctement déroulée. Vous pouvez dès à présent vous connecter à votre compte.";
                }else{
                    $notification = "L'email que vous avez renseigné existe déjà.";
                }

        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'notification' => $notification
        ]);
    }

    
}
