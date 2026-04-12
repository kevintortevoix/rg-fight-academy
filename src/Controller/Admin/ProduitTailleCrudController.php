<?php

namespace App\Controller\Admin;

use App\Entity\ProduitTaille;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class ProduitTailleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProduitTaille::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            AssociationField::new('produit'),
            ChoiceField::new('taille')->setChoices([
                'TU' => 'TU',
                'XS' => 'XS',
                'S'  => 'S',
                'M'  => 'M',
                'L'  => 'L',
                'XL' => 'XL',
                'XXL' => 'XXL',
            ]),
            IntegerField::new('stock'),
        ];
    }
}