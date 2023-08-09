<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Marque;
use App\Entity\Carrier;
use App\Entity\Product;
use App\Entity\Category;
use App\Controller\Admin\OrderCrudController;
use App\Entity\Header;
use App\Entity\RentCar;
use App\Entity\RentCategory;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(OrderCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Symfocar');
            
    }

    public function configureMenuItems(): iterable 
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home', Order::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Products', 'fas fa-car', Product::class);
        yield MenuItem::linkToCrud('Orders', 'fa-solid fa-bag-shopping', Order::class);
        yield MenuItem::linkToCrud('Transporteurs', 'fa-solid fa-truck-fast', Carrier::class);
        yield MenuItem::linkToCrud('Header', 'fa-solid fa-tv', Header::class);
        yield MenuItem::linkToCrud('Location', 'fa-solid fa-car-on', RentCar::class);
        yield MenuItem::linkToCrud('Location-Gamme', 'fa-solid fa-layer-group', RentCategory::class);
    } 
}


