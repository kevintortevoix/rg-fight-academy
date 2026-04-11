<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('utilisateur.nom', 'Nom'),
            TextField::new('utilisateur.prenom', 'Prénom'),

            DateTimeField::new('dateReservation', 'Date de réservation')->setFormat('dd/MM/yyyy'),

            AssociationField::new('cours', 'Cours'),
            
            TextField::new('dateCours', 'Date du cours')->onlyOnIndex(),

            TextField::new('heureDebutCours', 'Heure de début')->onlyOnIndex(),
            TextField::new('heureFinCours', 'Heure de fin')->onlyOnIndex(),

            IntegerField::new('nombreParticipants', 'Participants'),
            
            TextField::new('statut'),

        ];
    }
}
