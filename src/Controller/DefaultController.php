<?php

namespace App\Controller;

use App\Service\ImdbService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController

{
    /**
     * @Route("/", name="app_index")
     */
    public function index(CategoryRepository $categoryRepository): Response

    {
        $imdbApi= new ImdbService();
        $data= $imdbApi->getImdbData();


        return $this->render('/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'data'=> $data['items']
    ]);
    }
}