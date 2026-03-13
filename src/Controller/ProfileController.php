<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

final class ProfileController extends AbstractController
{
    #[Route('/profil', name: 'profil')]
    #[IsGranted('ROLE_USER')] // Seuls les utilisateurs connectés peuvent accéder
    public function index(): Response
    {
        // $this->getUser() retourne l'utilisateur connecté
        return $this->render('profile/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
