<?php

namespace App\Controller;

use App\Entity\Episode;
use App\Form\EpisodeType;
use App\Service\Slugify;
use App\Repository\EpisodeRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;


/**
 * @Route("/episode")
 */
class EpisodeController extends AbstractController
{
    /**
     * @Route("/", name="episode_index", methods={"GET"})
     */
    public function index(EpisodeRepository $episodeRepository, CategoryRepository $categoryRepository): Response
    {
        return $this->render('episode/index.html.twig', [
            'episodes' => $episodeRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="episode_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, Slugify $slugify, MailerInterface $mailer): Response
    {
        $episode = new Episode();
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);

            $entityManager->persist($episode);
            $entityManager->flush();

            $email = (new Email())

                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Un nouvel épisode vient d\'être publié !')
                ->html($this->renderView('episode/newEpisodeEmail.html.twig', ['episode' => $episode]));


        $mailer->send($email);

            return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/new.html.twig', [
            'episode' => $episode,
            'form' => $form,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/{slug}", name="episode_show", methods={"GET"})
     */
    public function show(Episode $episode, CategoryRepository $categoryRepository): Response
    {
        return $this->render('episode/show.html.twig', [
            'episode' => $episode,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="episode_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Episode $episode, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository, Slugify $slugify): Response
    {
        $form = $this->createForm(EpisodeType::class, $episode);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slugify->generate($episode->getTitle());
            $episode->setSlug($slug);

            $entityManager->flush();

            return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('episode/edit.html.twig', [
            'episode' => $episode,
            'form' => $form,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="episode_delete", methods={"POST"})
     */
    public function delete(Request $request, Episode $episode, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$episode->getId(), $request->request->get('_token'))) {
            $entityManager->remove($episode);
            $entityManager->flush();
        }

        return $this->redirectToRoute('episode_index', [], Response::HTTP_SEE_OTHER);
    }
}
