<?php

namespace App\Controller;

use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Classe\Mail;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $content = "Bonjour,<br/><br/><br/>Vous avez reçu une nouvelle demande de contact depuis le site Symfocar.<br/><br/>";
            $content .= "<br><hr><br><br><p style='text-align: center; font-weight: bold; text-decoration: underline;'>Voici les détails de la demande :</p><br/><br/><br/>";
            $content .= "Nom : <b>".$form->get('firstname')->getData()."</b><br/><br/>";
            $content .= "Prénom : <b>".$form->get('lastname')->getData()."</b><br/><br/>";
            $content .= "Email : ".$form->get('email')->getData()."<br/><br/>";
            $content .= "Téléphone : ".$form->get('phone')->getData()."<br/><br/>";
            $content .= "<br/><b>Message :</b> <br/><br/><span style='font-style: italic; line-height: 1.5; font-weight: 300;'>".$form->get('message')->getData()."</span><br/><br/><br/><br/><br/>";
            $content .= "<hr><br><br>A bientôt !";


            $mail = new Mail();
            $mail->send(
                'gabistam@yahoo.fr',
                'Symfocar',
                'Vous avez reçu une nouvelle demande de contact',
                $content
            );

            $this->addFlash('notice', 'Votre message a bien été envoyé !');

            return $this->redirectToRoute('app_contact');
            
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
