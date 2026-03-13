<?php

namespace App\Controller\Admin;

use App\Entity\Commande;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CommandeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Commande::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('utilisateur.nom', 'Nom'),
            TextField::new('utilisateur.prenom', 'Prénom'),
            DateTimeField::new('dateCommande', 'Date de commande'),
            NumberField::new('total')->setNumDecimals(2),
            ChoiceField::new('statut')->setChoices([
                "En attente d'acceptation" => "En attente d'acceptation",
                "Validée"   => "Validee",
                "Retrait et paiment au dojo"  => "Retrait et paiement au dojo",
            ]),
            AssociationField::new('commandeProduits', 'Produits')
            
            // TextEditorField::new('description'),
        ];
    }
}
