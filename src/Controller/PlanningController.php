<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CoursRepository;

final class PlanningController extends AbstractController
{
    #[Route('/planning', name: 'app_planning')]
    public function index(CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->findAll();

        // On organise les cours par jour
        $planning = [];
        foreach ($cours as $cours) {
        $planning[$cours->getJour()][] = $cours;
        }

        return $this->render('planning/index.html.twig', [
            'planning' => $planning,
        ]);
    }
}
