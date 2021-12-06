<?php

namespace App\Controller;
use App\Repository\CategoryRepository;
use App\Entity\Review;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends AbstractController
{
    /**
     * @Route("/review", name="review_index")
     */
    public function index(ReviewRepository $reviewRepository,CategoryRepository $categoryRepository ): Response
    {
        return $this->render('review/index.html.twig', [
            'reviews' => $reviewRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/review/new", name="review_new")
     */
    public function new(Request $request, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager): Response
    {
        $review = new Review();
        $form = $this->createForm(ReviewType::class, $review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($review);
            $entityManager->flush();
            return $this->redirectToRoute('review_index');
        }

        return $this->render('review/new.html.twig', [
            "form" => $form->createView(),
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
