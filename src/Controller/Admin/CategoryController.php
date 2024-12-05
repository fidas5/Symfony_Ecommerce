<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/category', name: 'admin_category_')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        // Retrieve categories sorted by 'categoryOrder'
        $categories = $categoryRepository->findBy([], ['categoryOrder' => 'ASC']);

        return $this->render('admin/category/index.html.twig', [
            'categories' => $categories,  // Ensure this is correctly passed to the view
        ]);
    }

    #[Route('/new', name: 'new')]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');  // Ensure the user has the right role

        $category = new Category();
        $categoryForm = $this->createForm(CategoryFormType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $slug = $slugger->slug($category->getName());  // Generate slug from category name
            $category->setSlug($slug);

            $em->persist($category);  // Persist the new category
            $em->flush();  // Save changes to the database

            $this->addFlash('success', 'Category created successfully!');
            return $this->redirectToRoute('admin_category_index');  // Redirect to the category list page
        }

        return $this->render('admin/category/add.html.twig', [
            'categoryForm' => $categoryForm->createView(),  // Pass the form view to the template
        ]);
    }

    #[Route('/edit/{id}', name: 'edit')]
    public function edit(Category $category, Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');  // Ensure the user has the right role

        $categoryForm = $this->createForm(CategoryFormType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            // Regenerate the slug if the name was changed
            $slug = $slugger->slug($category->getName());
            $category->setSlug($slug);

            // Update the category order (if it's provided)
            $categoryOrder = $categoryForm->get('categoryOrder')->getData();
            $category->setCategoryOrder($categoryOrder);

            $em->flush();  // Persist changes to the database

            $this->addFlash('success', 'Category updated successfully!');
            return $this->redirectToRoute('admin_category_index');  // Redirect to the category list page
        }

        return $this->render('admin/category/edit.html.twig', [
            'categoryForm' => $categoryForm->createView(),  // Pass the form view to the template
            'category' => $category,  // Pass the current category data to the template
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Category $category, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');  // Ensure the user has the right role

        // Check if the category has products or subcategories
        if (count($category->getProducts()) > 0 || count($category->getCategories()) > 0) {
            // Handle products or subcategories: could be reassigning them or preventing deletion
            $this->addFlash('error', 'Category has associated products or subcategories and cannot be deleted.');
            return $this->redirectToRoute('admin_category_index');
        }

        // Delete the category
        $em->remove($category);  // Remove the category
        $em->flush();  // Save changes to the database

        $this->addFlash('success', 'Category deleted successfully!');
        return $this->redirectToRoute('admin_category_index');  // Redirect to the category list page
    }
}
