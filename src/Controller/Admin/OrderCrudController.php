<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    private $entityManager;
    private $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager, AdminUrlGenerator $crudUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->crudUrlGenerator = $crudUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation = Action::new('updatePreparation', 'Préparation en cours', 'fas fa-box-open')->linkToCrudAction('updatePreparation');
        $updateDelivery = Action::new('updateDelivery', 'Livraison en cours', 'fas fa-truck')->linkToCrudAction('updateDelivery');
        $updateDelivered = Action::new('updateDelivered', 'Livrée', 'fas fa-check')->linkToCrudAction('updateDelivered');
        $updateCancel = Action::new('updateCancel', 'Annuler', 'fas fa-undo')->linkToCrudAction('updateCancel');

        return $actions
        ->add('detail', $updatePreparation)
        ->add('detail', $updateDelivery)
        ->add('detail', $updateDelivered)
        ->add('detail', $updateCancel)
        ->add('index', 'detail');
    }

    public function updatePreparation(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:white; border:none; border-radius:25px;background-color:blue;padding:7px 10px;'>La commande ".$order->getReference()." est bien <b>en cours de préparation</b></span>");

        $url = $this->crudUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        $mail = new Mail();
        $content = "Bonjour " . $order->getUser()->getLastname() . "<br/><br/>Votre commande n° <b>". $order->getReference() . "</b> est en cours de préparation.<br/><br/>Nous vous tiendrons informé de l'envoi de votre commande dès que possible.<br/><br/>A bientôt sur notre site.";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande Symfocar est en cours de préparation', $content);


        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:white; border:none; border-radius:25px;background-color:blue;padding:7px 10px;'>La commande ".$order->getReference()." est bien <b>en cours de livraison</b></strong></span>");

        $url = $this->crudUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        $mail = new Mail();
        $content = "Bonjour " . $order->getUser()->getLastname() . "<br/><br/>Votre commande n° <b>". $order->getReference() . "</b> est en cours de livraison.<br/><br/>Nous vous tiendrons informé de l'envoi de votre commande dès que possible.<br/><br/>A bientôt sur notre site.";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande Symfocar est en cours de livraison', $content);


        return $this->redirect($url);
    }

    public function updateDelivered(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        $order->setState(4);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:white; border:none; border-radius:25px;background-color:green;padding:7px 10px;'><strong>La commande ".$order->getReference()." a bien été <b>livrée</b></strong></span>");

        $url = $this->crudUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        $mail = new Mail();
        $content = "Bonjour " . $order->getUser()->getLastname() . "<br/><br/>Nous vous informons que votre commande n° <b>". $order->getReference() . "</b> a été livrée.<br/><br/>Veuillez consulter votre compte dès que possible pour plus d'information.<br/><br/>A bientôt sur notre site.";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande Symfocar est livrée', $content);

        return $this->redirect($url);
    }

    public function updateCancel(AdminContext $context)
    {
        $order = $context->getEntity()->getInstance();

        $order->setState(5);
        $this->entityManager->flush();

        $this->addFlash('notice', "<span style='color:white; border:none; border-radius:25px;background-color:red;padding:7px 10px;'>La commande ".$order->getReference()." est bien <b>annulée</b></strong></span>");

        $url = $this->crudUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        $mail = new Mail();
        $content = "Bonjour " . $order->getUser()->getLastname() . "<br/><br/>Nous vous informons que votre commande n° <b>". $order->getReference() . "</b> est annulée.<br/><br/>Veuillez consulter votre compte dès que possible pour plus d'information.<br/><br/>A bientôt sur notre site.";
        $mail->send($order->getUser()->getEmail(), $order->getUser()->getFirstname(), 'Votre commande Symfocar est annulée', $content);

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(['id' => 'DESC']);
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt', 'Passée le'),
            TextField::new('user.getFullName', 'Utilisateur'),
            TextEditorField::new('delivery', 'Adresse de livraison')->onlyOnDetail(),
            MoneyField::new('totalTTC', 'Total produit')->setCurrency('EUR'),
            TextField::new('carrierName', 'Transporteur'),
            MoneyField::new('carrierPrice', 'Frais de port')->setCurrency('EUR'),
            ChoiceField::new('state', 'Statut')->setChoices([
                'Non payée' => '0',
                'Payée' => '1',
                'Préparation en cours' => '2',
                'Livraison en cours' => '3',
                'Livrée' => '4',
                'Annulée' => '5'
            ]),
            ArrayField::new('orderDetails', 'Produits achetés')->hideOnIndex(),
            
            
        ];
    }
    
}
