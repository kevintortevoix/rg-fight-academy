<?php

namespace App\Tests\Entity;

use App\Entity\Commande;
use App\Entity\Utilisateur;
use PHPUnit\Framework\TestCase;

class CommandeTest extends TestCase
{
    /**
     * Vérifie que le setter et le getter du statut fonctionnent correctement :
     * le statut enregistré doit être identique à celui qu'on a défini.
     */
    public function testSetEtGetStatut(): void
    {
        $commande = new Commande();
        $commande->setStatut('En attente');
        $this->assertSame('En attente', $commande->getStatut());
    }

    /**
     * Vérifie que le setter et le getter du total fonctionnent correctement :
     * le total enregistré doit être identique à celui qu'on a défini.
     */
    public function testSetEtGetTotal(): void
    {
        $commande = new Commande();
        $commande->setTotal(49.99);
        $this->assertSame(49.99, $commande->getTotal());
    }

    /**
     * Vérifie qu'une commande sans utilisateur associé retourne null
     * lorsqu'on appelle getNomUtilisateur().
     */
    public function testGetNomUtilisateur(): void
    {
        $commande = new Commande();
        $this->assertNull($commande->getNomUtilisateur());
    }

    /**
     * Vérifie que getNomUtilisateur() retourne bien le nom de l'utilisateur
     * associé à la commande.
     * On crée un utilisateur avec un nom, on l'associe à une commande,
     * et on s'assure que c'est bien son nom qui est renvoyé.
     */
    public function testGetNomUtilisateur2(): void
    {
        // Création d'un utilisateur avec le nom "Dupont"
        $utilisateur = new Utilisateur();
        $utilisateur->setNom('Dupont');

        // Association de l'utilisateur à la commande
        $commande = new Commande();
        $commande->setUtilisateur($utilisateur);

        // On vérifie que getNomUtilisateur() renvoie bien "Dupont"
        $this->assertSame('Dupont', $commande->getNomUtilisateur());
    }
}