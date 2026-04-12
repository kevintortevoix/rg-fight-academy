<?php

namespace App\Controller\Admin;

use App\Entity\Produit;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class ProduitCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Produit::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom'),
            NumberField::new('prix')->setNumDecimals(2),
            AssociationField::new('categorie'),
            TextareaField::new('description'),
            CollectionField::new('produitTailles', 'Tailles & stocks')
                ->setEntryType(\App\Form\ProduitTailleType::class)
                ->setFormTypeOptions([
                    'by_reference' => false,
                ])
                ->onlyOnForms(),
            TextField::new('image', 'Nom du fichier image')
                ->onlyOnForms(),
            ImageField::new('image')
                ->setBasePath('/uploads/images')
                ->onlyOnIndex(),
        ];
    }
}
