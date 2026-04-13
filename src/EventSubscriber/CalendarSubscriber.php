<?php

namespace App\EventSubscriber;

use App\Repository\CoursRepository;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\SetDataEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    public function __construct(private CoursRepository $coursRepository) {}

    public static function getSubscribedEvents(): array
    {
        return [
            SetDataEvent::class => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(SetDataEvent $setDataEvent): void
    {
        $cours = $this->coursRepository->findAll();

        $jours = [
            'Lundi'    => 'Monday',
            'Mardi'    => 'Tuesday',
            'Mercredi' => 'Wednesday',
            'Jeudi'    => 'Thursday',
            'Vendredi' => 'Friday',
            'Samedi'   => 'Saturday',
            'Dimanche' => 'Sunday',
        ];

        foreach ($cours as $cours) {
            $jourEn = $jours[$cours->getJour()];

            $debut = new \DateTime('this ' . $jourEn);
            $debut->setTime(
                $cours->getHeureDebut()->format('H'),
                $cours->getHeureDebut()->format('i')
            );

            $fin = new \DateTime('this ' . $jourEn);
            $fin->setTime(
                $cours->getHeureFin()->format('H'),
                $cours->getHeureFin()->format('i')
            );


            $couleurs = [
                "JJB Baby's 3-6 ans"    => '#d1d5db',  // gris 
                "JJB Kid's 6-15 ans"    => '#3b82f6',  // bleue
                "Grappling Adultes"     => '#9333ea',  // violette
                "Préparation Physique"  => '#92400e',  // marron
                "JJB Adultes"           => '#1a1a1a',  // noire

            ];

            $couleur = $couleurs[$cours->getNom()] ?? '#333333';

            $event = new Event($cours->getNom(), $debut, $fin);
            $event->addOption('url', '/reservation/' . $cours->getId());
            $event->addOption('backgroundColor', $couleur);
            $event->addOption('borderColor', $couleur);
            $event->addOption('textColor', '#ffffff');

            $setDataEvent->addEvent($event);
        }
    }
}
