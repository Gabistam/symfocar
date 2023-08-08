<?php

namespace App\Controller;

use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    private $entityManagerInterface;

    public function __construct(EntityManagerInterface $entityManagerInterface)
    {
        $this->entityManagerInterface = $entityManagerInterface;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {

        // $products = $this->entityManagerInterface->getRepository(Product::class)->findByIsBest(1);

        $products = $this->entityManagerInterface->getRepository(Product::class)->findBestProducts(1);


        $headers = $this->entityManagerInterface->getRepository(Header::class)->findAll();
        
        return $this->render('home/index.html.twig', [
            'products' => $products,
            'headers' => $headers
        ]);

    }
}
