<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/order', name: 'app_order_')]
class OrderController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $em): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $cart = $session->get('cart', []);

        if($cart === []){
            $this->addFlash('warning', 'Your cart is empty');
            return $this->redirectToRoute('app');
        }

        $order = new Order();

        $order->setUser($this->getUser());
        $order->setOrderTag(uniqid());

        foreach($cart as $item => $quantity){
            $orderDetail = new OrderDetail();
            $product = $productRepository->find($item);
            $price = $product->getPrice();
            $orderDetail->setProduct($product);
            $orderDetail->setPrice($price);
            $orderDetail->setQuantity($quantity);

            $order->addOrderDetail($orderDetail);
        }

        $em->persist($order);
        $em->flush();

        $session->remove('cart');

        $this->addFlash('success', 'Order has been created successfully');
        return $this->redirectToRoute(('app'));
    }
}
