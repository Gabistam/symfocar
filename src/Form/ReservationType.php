<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\RentCar;
use App\Entity\Reservation;
use App\Repository\RentCarRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rentCar', EntityType::class, [
                'class' => RentCar::class,
                'query_builder' => function (RentCarRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->where('p.isDispo = :isAvailable')
                        ->setParameter('isAvailable', true);
                },
                'choice_label' => 'name',
                'expanded' => true, // Les choix sont rendus en tant que boutons radio
                'label' => 'Choisissez une voiture',
            ])
            ->add('startDate', HiddenType::class)  // champ caché pour la date de début
            ->add('endDate', HiddenType::class)    // champ caché pour la date de fin
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
