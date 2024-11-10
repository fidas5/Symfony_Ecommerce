<?php

namespace App\Controller;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/products', name: 'products_')]
class ProductsController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(EntityManagerInterface $em): Response
    {
        $products = $em->getRepository(Product::class)->findAll();

        return $this->render('products/index.html.twig', ['products' => $products]);
    }

    #[Route('/{slug}', name: 'details')]
    public function details(EntityManagerInterface $em, ?string $slug = null): Response
    {
        if ($slug === null) {
            $this->addFlash('error', 'Product not found');
            return $this->redirectToRoute('products_index');
        }

        $product = $em->getRepository(Product::class)->findOneBy(['slug' => $slug]);
        if ($product === null) {
            $this->addFlash('error', 'Product not found');
            return $this->redirectToRoute('products_index');
        }
        
        return $this->render('products/details.html.twig', ['product' => $product]);
    }
}
