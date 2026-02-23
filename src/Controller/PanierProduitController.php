<?php

namespace App\Controller;

use App\Entity\PanierProduit;
use App\Form\PanierProduitType;
use App\Repository\PanierProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/panier/produit')]
final class PanierProduitController extends AbstractController
{
    #[Route(name: 'app_panier_produit_index', methods: ['GET'])]
    public function index(PanierProduitRepository $panierProduitRepository): Response
    {
        return $this->render('panier_produit/index.html.twig', [
            'panier_produits' => $panierProduitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_panier_produit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $panierProduit = new PanierProduit();
        $form = $this->createForm(PanierProduitType::class, $panierProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($panierProduit);
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier_produit/new.html.twig', [
            'panier_produit' => $panierProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_produit_show', methods: ['GET'])]
    public function show(PanierProduit $panierProduit): Response
    {
        return $this->render('panier_produit/show.html.twig', [
            'panier_produit' => $panierProduit,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_panier_produit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PanierProduit $panierProduit, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PanierProduitType::class, $panierProduit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('panier_produit/edit.html.twig', [
            'panier_produit' => $panierProduit,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_panier_produit_delete', methods: ['POST'])]
    public function delete(Request $request, PanierProduit $panierProduit, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$panierProduit->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($panierProduit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_panier_produit_index', [], Response::HTTP_SEE_OTHER);
    }
}
