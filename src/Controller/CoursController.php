<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\CoursRepository;

final class CoursController extends AbstractController
{

    #[Route('/cours/calendar-data', name: 'app_cours_calendar_data')]
    public function calendarData(CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->findAll();

        $jours = [
            'Lundi'    => 1,
            'Mardi'    => 2,
            'Mercredi' => 3,
            'Jeudi'    => 4,
            'Vendredi' => 5,
            'Samedi'   => 6,
            'Dimanche' => 0,
        ];

        $events = [];
        foreach ($cours as $cours) {
            $events[] = [
                'id'    => $cours->getId(),
                'title' => $cours->getNom(),
                'daysOfWeek' => [$jours[$cours->getJour()]],
                'startTime'  => $cours->getHeureDebut()->format('H:i'),
                'endTime'    => $cours->getHeureFin()->format('H:i'),
                'url'        => '/reservation/' . $cours->getId(),
            ];
        }

        return $this->json($events);
    }
    #[Route('/cours', name: 'app_cours')]
    public function index(CoursRepository $coursRepository): Response
    {
        $cours = $coursRepository->findAll();

        $planning = [];
        foreach ($cours as $cours) {
            $planning[$cours->getJour()][] = $cours;
        }

        return $this->render('cours/index.html.twig', [
            'planning' => $planning,
        ]);
    }
}
