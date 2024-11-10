<?php

namespace App\Controller\Admin;
use App\Entity\User;
use App\Repository\OrderRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/users', name: 'admin_users_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/users/index.html.twig', [
            'users' => $users
        ]);
    }

    #[Route('/orders', name: 'orders')]
    public function orders(OrderRepository $orderRepository): Response
    {
        $orders = $orderRepository->findBy([], ['id' => 'DESC']);

        return $this->render('admin/users/orders.html.twig', [
            'orders' => $orders
        ]);
    }

    #[Route('/delete/{id}', name: 'delete')]
    public function delete(User $user, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('USER_DELETE', $user);
        
        $em->remove($user);
        $em->flush();

        $this->addFlash('success', 'User deleted successfully!');
        return $this->redirectToRoute('admin_users_index');
    }
}
