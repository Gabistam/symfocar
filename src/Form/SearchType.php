<?php

namespace App\Form;

use App\Classe\Search;
use App\Entity\Product;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('string', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => "Votre recherche ...",
                    'class' => 'form-control-sm rounded-pill'
                ]
            ])
            ->add('categories', EntityType::class, [
                'label' => false,
                'required' => false,
                'class' => Category::class,
                'attr' => [
                    'class' => 'py-2'
                ],
                'multiple' => true,
                'expanded' => true

            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Filtrer',
                'attr' => [
                    'class' => 'btn-block btn-info current-btn rounded-pill'
                ]
            ]);
    }
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            // 'method' => 'GET',
            'csrf_protection' => false
        ]);
    }
    public function getBlockPrefix(): string
    {
        return '';
    }
}

