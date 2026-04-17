<?php

namespace App\Tests\Entity;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class CommandeTest extends TestCase
{
    public function testSetEtGetStatut(): void
    {
        $commande = new Commande();
        $commande->setStatut('En attente');
        $this->assertSame('En attente', $commande->getStatut());
    }

    public function testSetEtGetTotal(): void
    {
        $commande = new Commande();
        $commande->setTotal(49.99);
        $this->assertSame(49.99, $commande->getTotal());
    }

    public function testGetNomUtilisateur(): void
    {
        $commande = new Commande();
        $this->assertNull($commande->getNomUtilisateur());
    }

    public function testGetNomUtilisateurRetourneLeNom(): void
    {
        $utilisateur = new Utilisateur();
        $utilisateur->setNom('Dupont');

        $commande = new Commande();
        $commande->setUtilisateur($utilisateur);

        $this->assertSame('Dupont', $commande->getNomUtilisateur());
    }
}