<?php

namespace App\Controller;

use App\Entity\Actor;
use App\Repository\ActorRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/actor", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function index(ActorRepository $actorRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('actor/index.html.twig', [
            'actors' => $actorRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="show")
     * @return Response
     */
    public function show(Actor $actor, CategoryRepository $categoryRepository): Response
    {
        return $this->render('actor/show.html.twig', [
            'actor' => $actor,
            'programs' => $actor->getPrograms(),
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
