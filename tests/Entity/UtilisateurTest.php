<?php

namespace App\Tests\Entity;

use App\Entity\Utilisateur;
use App\Entity\Panier;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    /**
     * Vérifie qu'un utilisateur a toujours le rôle ROLE_USER par défaut,
     * même sans lui avoir assigné de rôle manuellement.
     */
    public function testGetRoles(): void
    {
        $utilisateur = new Utilisateur();
        $this->assertContains('ROLE_USER', $utilisateur->getRoles());
    }

    /**
     * Vérifie qu'un utilisateur sans panier retourne null
     * lorsqu'on appelle getPanierActif().
     */
    public function testGetPanierActif(): void
    {
        $utilisateur = new Utilisateur();
        $this->assertNull($utilisateur->getPanierActif());
    }

    /**
     * Vérifie que getPanierActif() retourne uniquement le panier
     * dont le statut est "actif", et non les autres paniers (ex: "valide").
     * On ajoute deux paniers à l'utilisateur et on s'assure que
     * c'est bien le bon qui est renvoyé.
     */
    public function testGetPanierActif2(): void
    {
        $utilisateur = new Utilisateur();

        // Création d'un panier avec le statut "valide" (ne doit pas être retourné)
        $panierValide = new Panier();
        $panierValide->setStatut('valide');

        // Création d'un panier avec le statut "actif" (doit être retourné)
        $panierActif = new Panier();
        $panierActif->setStatut('actif');

        $utilisateur->addPanier($panierValide);
        $utilisateur->addPanier($panierActif);

        // On vérifie que getPanierActif() renvoie bien le panier actif
        $this->assertSame($panierActif, $utilisateur->getPanierActif());
    }

    /**
     * Vérifie que le setter et le getter de l'email fonctionnent correctement :
     * l'email enregistré doit être identique à celui qu'on a défini.
     */
    public function testSetEtGetEmail(): void
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('test@example.com');
        $this->assertSame('test@example.com', $utilisateur->getEmail());
    }
}