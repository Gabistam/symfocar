<?php

namespace App\Form;

use App\Entity\Adress;
use App\Form\AdressType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('addresses', EntityType::class, [
                'label' => 'Adresse de livraison',
                'required' => true,
                'class'=> Adress::class,
                'multiple' => false,
                'expanded' => true,
                'attr' => [
                    'class' => 'col-md-6'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
