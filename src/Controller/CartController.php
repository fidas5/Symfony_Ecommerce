<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/cart', name: 'cart_')]
class CartController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ProductRepository $productRepository,SessionInterface $session)
    {
        $cart = $session->get('cart', []);
        $data = [];
        $total = 0;
        foreach($cart as $id => $quantity){
            $product = $productRepository->find($id);

            $data[] = [
                'product' => $product,
                'quantity' => $quantity
            ];
            $total += $product->getPrice() * $quantity;
        }
        return $this->render('cart/index.html.twig', [
            'data' => $data,
            'total' => $total
        ]);
    }

    #[Route('/add/{id}', name: 'add')]
    public function add(Product $product, SessionInterface $session)
    {
        $id = $product->getId();
        $cart = $session->get('cart', []);
        if(empty($cart[$id])){
            $cart[$id] = 1;
        } else {
            $cart[$id]++;
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute(('cart_index'));
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(Product $product, SessionInterface $session)
    {
        $id = $product->getId();
        $cart = $session->get('cart', []);
        if(!empty($cart[$id])){
            if($cart[$id] > 1){
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }
        $session->set('cart', $cart);
        return $this->redirectToRoute(('cart_index'));
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Product $product, SessionInterface $session)
    {
        $id = $product->getId();
        $cart = $session->get('cart', []);
        if(!empty($cart[$id])){
            unset($cart[$id]);
        }
        $session->set('cart', $cart);
        $this->addFlash('info', 'Product removed from cart!');
        return $this->redirectToRoute(('cart_index'));
    }

    #[Route('/empty', name: 'empty')]
    public function empty(SessionInterface $session)
    {
        $session->remove('cart');
        $this->addFlash('info', 'Cart is empty!');
        return $this->redirectToRoute(('cart_index'));
    }
}
