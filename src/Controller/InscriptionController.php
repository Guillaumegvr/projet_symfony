<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Wish;
use App\Form\UserType;
use App\Form\WishType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function add(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $user -> setRoles(['ROLE_USER']);

        $userForm = $this->createForm(UserType::class, $user);


        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $entityManager->persist($user);
            $plainTextPassword = $user -> getPassword();
            $hashedPassword = $passwordHasher->hashPassword($user, $plainTextPassword);
            $user -> setPassword($hashedPassword);
            $entityManager->flush();

            $this->addFlash("success", "Utilisateur ajouté avec succès");
            return $this->redirectToRoute('app_main_home');

        }
        return $this->render('inscription/index.html.twig', ["form" => $userForm->createView()
        ]);
    }
}
