<?php

namespace App\Tests\Entity;

use App\Entity\Utilisateur;
use App\Entity\Panier;
use PHPUnit\Framework\TestCase;

class UtilisateurTest extends TestCase
{
    public function testGetRolesContientToujoursRoleUser(): void
    {
        $utilisateur = new Utilisateur();
        $this->assertContains('ROLE_USER', $utilisateur->getRoles());
    }

    public function testGetPanierActifRetourneNullSiAucunPanier(): void
    {
        $utilisateur = new Utilisateur();
        $this->assertNull($utilisateur->getPanierActif());
    }

    public function testGetPanierActifRetourneLeBonPanier(): void
    {
        $utilisateur = new Utilisateur();

        $panierValide = new Panier();
        $panierValide->setStatut('valide');

        $panierActif = new Panier();
        $panierActif->setStatut('actif');

        $utilisateur->addPanier($panierValide);
        $utilisateur->addPanier($panierActif);

        $this->assertSame($panierActif, $utilisateur->getPanierActif());
    }

    public function testSetEtGetEmail(): void
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setEmail('test@example.com');
        $this->assertSame('test@example.com', $utilisateur->getEmail());
    }
}