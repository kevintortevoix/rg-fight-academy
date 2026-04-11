<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'contact')]
    public function contact(Request $request, MailerInterface $mailer, EntityManagerInterface $em): Response
    {
        // Création du formulaire basé sur ContactType
        $form = $this->createForm(ContactType::class);

        // Associe les données de la requête HTTP au formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Récupère directement l'objet Contact rempli par le formulaire
            $contact = $form->getData();

            // Sauvegarde en base de données
            $em->persist($contact);
            $em->flush();

            // Prépare et envoie l'email
            $email = (new Email())
                ->from($contact->getEmail())        // expéditeur = email du visiteur
                ->to('toi@example.com')             // ton adresse de réception
                ->subject('Nouveau message de contact')
                ->html('<p>' . $contact->getMessage() . '</p>');

            $mailer->send($email);

            // Message flash de confirmation
            $this->addFlash('success', 'Message envoyé !');

            // Redirige pour éviter la resoumission du formulaire
            return $this->redirectToRoute('contact');
        }

        // Affiche le formulaire
        return $this->render('accueil/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}