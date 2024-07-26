<?php
namespace App\Controller;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
class MainController extends AbstractController{

    #[Route('/', name: 'app_main_home')]
    public function home(){
        if($this->getUser()) {
            $userEmail = $this->getUser()->getEmail();
            return $this->render('main/home.html.twig', [
                'userEmail' => $userEmail  ]);
        }

        return $this->render('main/home.html.twig');

    }

    #[Route('/aboutUs', name: 'app_main_aboutUs')]
    public function aboutUs()
    {
        return $this->render('main/aboutUs.html.twig');
    }
}