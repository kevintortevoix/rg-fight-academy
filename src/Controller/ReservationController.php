<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Reservation;
use App\Repository\CoursRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;

final class ReservationController extends AbstractController
{
    #[Route('/reservation/{id}', name: 'app_reservation_new')]
    public function new(int $id, CoursRepository $coursRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $cours = $coursRepository->find($id);

        if (!$cours) {
            throw $this->createNotFoundException('Cours introuvable');
        }

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

    #[Route('/reservations', name: 'app_reservations')]
public function index(): Response
{
    $this->denyAccessUnlessGranted('ROLE_USER');

    /** @var \App\Entity\Utilisateur $user */
    $user = $this->getUser();

    return $this->render('reservation/liste.html.twig', [
        'reservations' => $user->getReservations(),
    ]);
}

    #[Route('/reservation/show/{id}', name: 'app_reservation_show')]
    public function show(int $id, ReservationRepository $reservationRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation introuvable');
        }

        if ($reservation->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès refusé');
        }

        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    #[Route('/reservation/delete/{id}', name: 'app_reservation_delete', methods: ['POST'])]
    public function delete(int $id, Request $request, ReservationRepository $reservationRepository, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $reservation = $reservationRepository->find($id);

        if (!$reservation) {
            throw $this->createNotFoundException('Réservation introuvable');
        }

        if ($reservation->getUtilisateur() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Accès refusé');
        }

        if ($this->isCsrfTokenValid('delete' . $reservation->getId(), $request->request->get('_token'))) {
            $em->remove($reservation);
            $em->flush();

            $this->addFlash('success', 'Réservation supprimée avec succès.');
        }

        return $this->redirectToRoute('profil');
    }
}