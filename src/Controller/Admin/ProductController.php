<?php

namespace App\Controller\Admin;

use App\Entity\Images;
use App\Entity\Product;
use App\Form\ProductFormType;
use App\Repository\ProductRepository;
use App\Service\PhotoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/products', name: 'admin_products_')]
class ProductController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/products/index.html.twig', [
            'product' => $products,
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PhotoService $photoService): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $product = new Product();

        $productForm = $this->createForm(ProductFormType::class, $product);

        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {

            $photos = $productForm->get('photo')->getData();

            foreach($photos as $photo){
                $folder = 'products';

                $file = $photoService->addPhoto($photo, $folder, 300, 300);

                $img = new Images();
                $img->setName($file);
                $product->addImage($img);
            }

            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            $em->persist($product);
            $em->flush();
            
            $this->addFlash('success', 'Product created successfully!');
            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/products/add.html.twig', [
            'productForm' => $productForm,
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function update(Product $product ,Request $request, EntityManagerInterface $em, SluggerInterface $slugger, PhotoService $photoService): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_UPDATE', $product);

        $productForm = $this->createForm(ProductFormType::class, $product);

        $productForm->handleRequest($request);

        if ($productForm->isSubmitted() && $productForm->isValid()) {

            $photos = $productForm->get('photo')->getData();

            foreach($photos as $photo){
                $folder = 'products';

                $file = $photoService->addPhoto($photo, $folder, 300, 300);

                $img = new Images();
                $img->setName($file);
                $product->addImage($img);
            }

            $slug = $slugger->slug($product->getName());
            $product->setSlug($slug);

            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('admin_products_index');
        }

        return $this->render('admin/products/edit.html.twig', [
            'productForm' => $productForm,
            'product' => $product,
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('PRODUCT_DELETE', $product);
        
        $em->remove($product);
        $em->flush();

        $this->addFlash('success', 'Product deleted successfully!');
        return $this->redirectToRoute('admin_products_index');
    }

    #[Route('/delete/photo/{id}', name: 'delete_photo', methods: ['DELETE'])]
    public function deletePhoto(Images $img, Request $request, EntityManagerInterface $em, PhotoService $photoService): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if($this->isCsrfTokenValid('delete' . $img->getId(), $data['_token']))
        {
            $photo = $img->getName();
            
            $photos = $img->getProduct()->getImages();

            if(count($photos) === 1){
                return new JsonResponse(['error' => 'Cannot delete the only remaining photo'], 400);
            }

            if($photoService->deletePhoto($photo, 'products', 300, 300)){
                $em->remove($img);
                $em->flush();

                return new JsonResponse(['success' => true], 200);
            }

            return new JsonResponse(['error' => 'Error while deleting photo'], 400);
        }

        return new JsonResponse(['error' => 'Token not valid'], 400);
    }
}
