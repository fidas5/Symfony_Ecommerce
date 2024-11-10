<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {

        $category = $categoryRepository->findBy([], ['categoryOrder' => 'ASC']);

        return $this->render('admin/category/index.html.twig', [
            'category' => $category
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $category = new Category();

        $categoryForm = $this->createForm(CategoryFormType::class, $category);

        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            $em->persist($category);
            $em->flush();

            $this->addFlash('success', 'Category created successfully!');
            return $this->redirectToRoute('admin_category_index');
        }

        return $this->render('admin/category/add.html.twig', [
            'categoryForm' => $categoryForm,
        ]);
    }
}