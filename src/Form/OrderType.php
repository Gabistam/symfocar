<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use App\Form\AdressType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
                    'class' => 'col-md-6'
                ]
            ])
            ->add('carrier', EntityType::class, [
                'label' => 'Choisissez votre transporteur',
                'required' => true,
                'class'=> Carrier::class,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'col-md-9'
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
