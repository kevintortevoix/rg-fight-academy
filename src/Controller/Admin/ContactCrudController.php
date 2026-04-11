<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Event\AfterEntityUpdatedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class ContactCrudController extends AbstractCrudController implements EventSubscriberInterface
{
    public function __construct(private MailerInterface $mailer) {}

    public static function getSubscribedEvents(): array
    {
        return [
            AfterEntityUpdatedEvent::class => 'sendReponse',
        ];
    }

    public function sendReponse(AfterEntityUpdatedEvent $event): void
    {
        $contact = $event->getEntityInstance();

        // On vérifie que c'est bien un Contact avec une réponse
        if (!$contact instanceof Contact || !$contact->getReponse()) {
            return;
        }

        $email = (new Email())
            ->from('rgfightacademy@example.com')   // ton email
            ->to($contact->getEmail())              // email du visiteur
            ->subject('Réponse à votre message - RG Fight Academy')
            ->html('<p>' . $contact->getReponse() . '</p>');

        $this->mailer->send($email);
    }

    public static function getEntityFqcn(): string
    {
        return Contact::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('nom')->setDisabled(),           // lecture seule
            EmailField::new('email')->setDisabled(),        // lecture seule
            TextareaField::new('message')->setDisabled(),   // lecture seule
            DateTimeField::new('createdAt')->hideOnForm(),
            TextareaField::new('reponse', 'Votre réponse'), // ✅ champ réponse
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW);  // on ne crée pas de message
    }
}