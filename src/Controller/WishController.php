<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\WishRepository;
use App\Service\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\DocBlock\Description;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
#[Route('/wish', name: 'app_wish_')]
class WishController extends AbstractController
{
    #[Route("", name: 'list')]
    public function list(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findPublishWishWithCategory();
        return $this->render('wish/list.html.twig',[
            'wishes' => $wishes
        ]);
    }


    #[Route("/details/{id}", name: 'detail', requirements: ['id' => '\d+'])]
    public function details(int $id, WishRepository $wishRepository): Response
    {
        $wish = $wishRepository->find($id);
        return $this->render('wish/details.html.twig', [
            'wish' => $wish
        ]);
    }


    #[Route("/add", name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager, Censurator $censurator): Response
    {
        $wish = new Wish();
        $wish->setDateCreated(new \DateTime());
        $wish->setPublished(true);

        $wishForm = $this->createForm(WishType::class, $wish);


        $wishForm->handleRequest($request);
        if ($wishForm->isSubmitted() && $wishForm->isValid()) {
            $entityManager->persist($wish);
            if($wish->getDescription()){
                $wish->setDescription($censurator->purify($wish->getDescription()));
            }

            $entityManager->flush();

            $this->addFlash("success", "Wish added successfully");
            return $this->redirectToRoute('app_wish_detail', ['id' => $wish->getId()]);

        }
        return $this->render('wish/add.html.twig', ["wishForm" => $wishForm->createView()
        ]);
    }
}
