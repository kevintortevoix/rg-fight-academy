<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $dateReservation = null;

    #[ORM\Column(length: 20)]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateur $utilisateur = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cours $cours = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(\DateTimeInterface $dateReservation): static
    {
        $this->dateReservation = $dateReservation;
        return $this;
    }

    public function getHeureDebutCours(): ?string
    {
        return $this->cours?->getHeureDebut()?->format('H:i');
    }

    public function getHeureFinCours(): ?string
    {
        return $this->cours?->getHeureFin()?->format('H:i');
    }

    public function getDateCours(): ?string
{
    if (!$this->cours) return null;

    $jourCours = $this->cours->getJour();
    $jours = [
        'Lundi' => 'Monday',
        'Mardi' => 'Tuesday',
        'Mercredi' => 'Wednesday',
        'Jeudi' => 'Thursday',
        'Vendredi' => 'Friday',
        'Samedi' => 'Saturday',
        'Dimanche' => 'Sunday',
    ];

    $jourEn = $jours[$jourCours] ?? $jourCours;
    $prochaine = new \DateTime('next ' . $jourEn);

    return $jourCours . ' ' . $prochaine->format('d/m/Y');
}

    public function getJourCours(): ?string
    {
        return $this->cours ? $this->cours->getJour() : null;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }


    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }
    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;
        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }
    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;
        return $this;
    }
}
