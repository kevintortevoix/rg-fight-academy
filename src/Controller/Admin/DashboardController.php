<?php

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\ProduitTaille;
use App\Entity\Contact;
use App\Entity\Cours;
use App\Entity\Reservation;


#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): RedirectResponse
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect(
            $adminUrlGenerator
                ->setController(\App\Controller\Admin\ProduitCrudController::class)
                ->generateUrl()
        );
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('RG Fight Academy');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fas fa-home');
        yield MenuItem::linkToRoute('Messages', 'fas fa-envelope', "admin_contact_index");
        yield MenuItem::linkToRoute('Utilisateurs', 'fa-solid fa-users', "admin_utilisateur_index");
        yield MenuItem::linkToRoute('Produits', 'fa-solid fa-box', "admin_produit_index");
        yield MenuItem::linkToUrl('Tailles', 'fa-solid fa-ruler', '/admin/produit-taille');
        yield MenuItem::linkToRoute('Catégories', 'fa-solid fa-list', "admin_categorie_index");
        yield MenuItem::linkToRoute('Commandes', 'fas fa-shopping-cart', "admin_commande_index");
        yield MenuItem::linkToRoute('Cours', 'fas fa-dumbbell', "admin_cours_index");
        yield MenuItem::linkToRoute('Réservations', 'fas fa-calendar', "admin_reservation_index");
        yield MenuItem::linkToRoute('Retour au site', 'fas fa-home', "app_accueil");

        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
