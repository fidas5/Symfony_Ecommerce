<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AppController extends AbstractController
{
    #[Route('/', name: 'app')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('app/index.html.twig', [
            'categories' => $categoryRepository->findBy([], ['categoryOrder' => 'ASC']),
        ]);
    }
}
