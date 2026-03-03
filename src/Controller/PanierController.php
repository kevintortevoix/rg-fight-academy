<?php

namespace App\Controller;

use App\Entity\Panier;
use App\Entity\PanierProduit;
use App\Entity\Produit;
use App\Form\PanierType;
use App\Repository\PanierRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier')]
final class PanierController extends AbstractController
{
    #[Route(name: 'app_panier_index', methods: ['GET'])]
    public function index(PanierRepository $panierRepository): Response
    {
        /** @var \App\Entity\Utilisateur $utilisateur */
        $utilisateur = $this->getUser();

        if (!$utilisateur) {
            return $this->redirectToRoute("app_login");
        }

        $panier = $utilisateur->getPanierActif();

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
        ]);
    }

    #[Route('/ajouter/{id}', name: 'panier_ajouter')]
    public function ajouter(Produit $produit, EntityManagerInterface $em): Response
    {
        //  Récupère l’utilisateur actuellement connecté.

        /** @var \App\Entity\Utilisateur $utilisateur */
        $utilisateur = $this->getUser();

        // Si l’utilisateur n’est pas connecté, il est redirigé vers la page de connexion.
        if (!$utilisateur) {
            return $this->redirectToRoute('app_login');
        }
        // On récupère le panier de l'utilisateur, s'il n'a pas encore de panier, panier sera null
        $panier = $utilisateur->getPanierActif();

        // Si pas de panier, on crée un nouveau panier (new Panier()), 
        // On le lie à l’utilisateur (setUtilisateur) et on le prépare à être enregistré en base (persist).
        if (!$panier) {
            $panier = new Panier();
            $panier->setUtilisateur($utilisateur);
            $em->persist($panier);
        }
        // On parcourt toutes les lignes du panier (PanierProduit).
        // Si la ligne correspond déjà au produit (===) → on incrémente la quantité.
        // Flush enregistre tout en base et on redirige vers la page du panier àprès avoir ajouté le produit
        foreach ($panier->getPanierProduits() as $ligne) {
            if ($ligne->getProduit() === $produit) {
                $ligne->setQuantite($ligne->getQuantite() + 1);
                $em->flush();
                return $this->redirectToRoute('app_panier_index');
            }
        }
        // Si le produit n’existe pas encore dans le panier, On crée une nouvelle ligne de panier avec ce produit.
        // On lie la ligne au panier (setPanier) et au produit (setProduit). On met la quantité à 1.
        // persist + flush → enregistre en base. Enfin, on redirige vers la page du panier.
        $ligne = new PanierProduit();
        $ligne->setPanier($panier);
        $ligne->setProduit($produit);
        $ligne->setQuantite(1);

        $em->persist($ligne);
        $em->flush();

        return $this->redirectToRoute('app_panier_index');
    }

    // #[Route('/new', name: 'app_panier_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $panier = new Panier();
    //     $form = $this->createForm(PanierType::class, $panier);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($panier);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('panier/new.html.twig', [
    //         'panier' => $panier,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_panier_show', methods: ['GET'])]
    // public function show(Panier $panier): Response
    // {
    //     return $this->render('panier/show.html.twig', [
    //         'panier' => $panier,
    //     ]);
    // }

    // #[Route('/{id}/edit', name: 'app_panier_edit', methods: ['GET', 'POST'])]
    // public function edit(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    // {
    //     $form = $this->createForm(PanierType::class, $panier);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->render('panier/edit.html.twig', [
    //         'panier' => $panier,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/{id}', name: 'app_panier_delete', methods: ['POST'])]
    // public function delete(Request $request, Panier $panier, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$panier->getId(), $request->getPayload()->getString('_token'))) {
    //         $entityManager->remove($panier);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_panier_index', [], Response::HTTP_SEE_OTHER);
    // }
}
