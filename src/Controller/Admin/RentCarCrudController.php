<?php

namespace App\Controller\Admin;

use App\Entity\RentCar;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RentCarCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RentCar::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name'),
            SlugField::new('slug')->setTargetFieldName('name'),
            ImageField::new('illustration')
            ->setBasePath('assets/images/uploads/rent/')
            ->setUploadDir('public/assets/images/uploads/rent/')
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setFormTypeOptions(['data_class' => null]),
            TextField::new('subtitle'),
            TextareaField::new('description'),
            BooleanField::new('isDispo'),
            MoneyField::new('price')->setCurrency('EUR'),
            AssociationField::new('rentCategory'),
        ];
    }
    
}
