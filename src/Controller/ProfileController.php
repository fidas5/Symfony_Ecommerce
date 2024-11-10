<?php

namespace App\Controller;

use App\Repository\OrderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use App\Security\AuthAuthenticator;
use App\Form\UpdateUserFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/profil', name: 'profile_')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findBy(['user' => $user]);
        
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'orders' => $orders,
        ]);
    }

    #[Route('/update/{id}', name: 'update')]
    public function register(UserRepository $userRepository ,Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {

        $id = $request->get('id');
        $user = $userRepository->find($id);

        $form = $this->createForm(UpdateUserFormType::class, $user);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
    
            $entityManager->flush();
    
            // Additional actions like sending an email can be added here
            $security->login($user, AuthAuthenticator::class, 'main');

            return $this->redirectToRoute('profile_index');
        }
    
        return $this->render('profile/edit.html.twig', [
            'updateUserForm' => $form,
        ]);
    }
}
