<?php

namespace App\Controller\Admin;

use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurCrudController extends AbstractCrudController
{
    /**
     * Service Symfony permettant de hasher les mots de passe
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * Injection du service de hashage dans le contrôleur
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Indique à EasyAdmin quelle entité ce CRUD gère
     */
    public static function getEntityFqcn(): string
    {
        return Utilisateur::class;
    }

    /**
     * Configuration des champs affichés dans l'interface admin
     */
    public function configureFields(string $pageName): iterable
    {
        // Champ mot de passe (uniquement dans les formulaires)
        $password = TextField::new('password')
            ->setLabel('Mot de passe')
            ->setFormType(PasswordType::class)
            ->onlyOnForms()
            ->setRequired($pageName === 'new'); // obligatoire uniquement lors de la création

        return [
            // ID de l'utilisateur (visible seulement dans la liste)
            IdField::new('id')->hideOnForm(),

            // Email de l'utilisateur
            EmailField::new('email'),

            // Nom
            TextField::new('nom'),

            // Prénom
            TextField::new('prenom'),

            // Gestion des rôles
            ChoiceField::new('roles')
                ->setLabel('Rôle')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices(),

            // Champ mot de passe
            $password,

            // Relation avec les paniers (visible seulement en détail)
            AssociationField::new('paniers')
                ->onlyOnDetail(),
        ];
    }

    /**
     * Méthode appelée lors de la création d'un utilisateur
     */
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        // Vérifie que l'entité est bien un utilisateur
        if (!$entityInstance instanceof Utilisateur) return;

        // Hash le mot de passe avant de sauvegarder
        $this->hashPassword($entityInstance);

        // Sauvegarde en base de données
        parent::persistEntity($entityManager, $entityInstance);
    }

    /**
     * Méthode appelée lors de la modification d'un utilisateur
     */
    public function updateEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Utilisateur) return;

        // Si un nouveau mot de passe est renseigné, on le hash
        if ($entityInstance->getPassword()) {
            $this->hashPassword($entityInstance);
        }

        // Mise à jour en base de données
        parent::updateEntity($entityManager, $entityInstance);
    }

    /**
     * Fonction qui transforme le mot de passe en version sécurisée (hashée)
     */
    private function hashPassword(Utilisateur $user): void
{
    // Si aucun mot de passe n'est saisi, on ne fait rien
    if (!$user->getPassword()) {
        return;
    }

    $hashedPassword = $this->passwordHasher->hashPassword(
        $user,
        $user->getPassword()
    );

    $user->setPassword($hashedPassword);
}
}