<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Reservation;
use App\Repository\CoursRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation_new')]
    public function new(int $id, CoursRepository $coursRepository, EntityManagerInterface $em): Response
    {
        // Vérifie que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cours = $coursRepository->find($id);

        if (!$cours) {
            throw $this->createNotFoundException('Cours introuvable');
        }

        // Crée la réservation
        $reservation = new Reservation();
        $reservation->setCours($cours);
        $reservation->setUtilisateur($this->getUser());
        $reservation->setDateReservation(new \DateTime());
        $reservation->setStatut('Réservé');

        $em->persist($reservation);
        $em->flush();

        return $this->render('reservation/index.html.twig', [
            'cours' => $cours,
        ]);
    }
}
