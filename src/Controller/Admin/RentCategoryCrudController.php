<?php

namespace App\Controller\Admin;

use App\Entity\RentCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class RentCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RentCategory::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
