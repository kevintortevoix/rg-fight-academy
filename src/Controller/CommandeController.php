<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Panier;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/commande')]
final class CommandeController extends AbstractController
{
    #[Route(name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository): Response
    {
        /** @var \App\Entity\Utilisateur $utilisateur */
        $utilisateur = $this->getUser();

        if (!$utilisateur) {
            return $this->redirectToRoute('app_login');
        }

        return $this->render('commande/index.html.twig', [
            'commandes' =>  $commandeRepository->findBy(['utilisateur' => $utilisateur]),
        ]);
    }



    // route sur /valider en methode POST

    /* 
        Recupérer l'user connecté
        faire les vérifications de connexions
        vérfier le CSRF TOKEN
        Récuperer le panier actif
        gérer les erreurs (ex : est ce qu'il est vide ?)
        calculer le total : donc boucler sur tous les prix des produits du panier

        Créer une commande avec new Commandes(), setutilisateur, datecommande, total, statut...
        Enregistrer en bdd
        message de confirmation addFlash
        rediriger vers le récapitulatif commande
    */

    #[Route('/valider', name: 'valider', methods: ['POST'])]
    public function valider(Request $request, EntityManagerInterface $entityManager, PanierRepository $panierRepository): Response
    {
        //  Vérifier si l'utilisateur est connecté
        /** @var \App\Entity\Utilisateur $utilisateur */
        $utilisateur = $this->getUser();

        if (!$utilisateur) {
            return $this->redirectToRoute('app_login');
        }

        //  Vérifier le CSRF TOKEN
        if (!$this->isCsrfTokenValid('valider_commande',
            $request->request->get('_token')
        )) {
            $this->addFlash('error', 'Token CSRF invalide.');
            return $this->redirectToRoute('app_panier_index');
        }

        //  Récupérer le panier actif
        $panier = $panierRepository->findOneBy([
            'utilisateur' => $utilisateur,
            'actif' => true
        ]);

        if (!$panier) {
            $this->addFlash('error', 'Aucun panier actif trouvé.');
            return $this->redirectToRoute('app_panier_index');
        }

        //  Vérifier si panier vide
        if ($panier->getPanierProduits()->isEmpty()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_panier_index');
        }

        //  Calcul du total
        $total = 0;

        foreach ($panier->getPanierProduits() as $produitPanier) {
            $prix = $produitPanier->getProduit()->getPrix();
            $quantite = $produitPanier->getQuantite();

            $total += $prix * $quantite;
        }

        //  Création de la commande
        $commande = new Commande();
        $commande->setUtilisateur($utilisateur);
        $commande->setDateCommande(new \DateTime());
        $commande->setTotal($total);
        $commande->setStatut('En attente');

        // Sauvegarde
        $entityManager->persist($commande);

        // Désactiver le panier
        $panier->setStatut("valide");

        $entityManager->flush();

        //  Message confirmation
        $this->addFlash('success', 'Commande validée avec succès !');

        //  Redirection vers récapitulatif
        return $this->redirectToRoute('app_commande_show', [
            'id' => $commande->getId()
        ]);
    }

    // #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $commande = new Commande();
    //     $form = $this->createForm(CommandeType::class, $commande);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($commande);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('commande/new.html.twig', [
    //         'commande' => $commande,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    // public function show(Commande $commande): Response
    // {
    //     return $this->render('commande/show.html.twig', [
    //         'commande' => $commande,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(CommandeType::class, $commande);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('commande/edit.html.twig', [
    //         'commande' => $commande,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    // public function delete(Request $request, Commande $commande, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete' . $commande->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($commande);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    // }
}
