<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    
    #[Route('/{slug}', name: 'list')]
    public function list(EntityManagerInterface $em, $slug = null, ProductRepository $productRepository, Request $request): Response
    {

        $category = $em->getRepository(Category::class)->findOneBy(['slug' => $slug]);
        if($slug == null || $category == null){
            $this->addFlash('error', 'Category not found');
            throw $this->createNotFoundException('Category not found');
        }

        $page = $request->query->getInt('page', 1);

        $product = $productRepository->findProductByPagination($page, $category->getSlug(), 5);

        return $this->render('category/list.html.twig', [
            'category' => $category,
            'product' => $product
        ]);
    }
}
