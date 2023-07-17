<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use App\Form\AdressType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Adresse de livraison',
                'required' => true,
                'class'=> Adress::class,
                'choices' => $user->getAdresses(),
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'col-md-12'
                ]
            ])
            ->add('carrier', EntityType::class, [
                'label' => 'Choisissez votre transporteur',
                'required' => true,
                'class'=> Carrier::class,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'col-md-12'
                ]
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Valider ma commande',
                'attr' => [
                    'class' => 'btn btn-primary btn-block col-md-12'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'user' => array()
        ]);
    }
}
