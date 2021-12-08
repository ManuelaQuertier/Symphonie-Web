<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;
use App\Service\Slugify;

use Symfony\Component\Mime\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController

{
    /**

     * @Route("/", name="index")
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository): Response

    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('program/index.html.twig', [

            'website' => 'Wild Séries', 'programs' => $programs, 'categories' => $categoryRepository->findAll()

        ]);
    }
    /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request, CategoryRepository $categoryRepository, Slugify $slugify, MailerInterface $mailer): Response
    {
        $program = new Program();

        $form = $this->createForm(ProgramType::class, $program);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);

            $entityManager->persist($program);
            $entityManager->flush();

            $email = (new Email())

                ->from($this->getParameter('mailer_from'))
                ->to('your_email@example.com')
                ->subject('Une nouvelle série vient d\'être publiée !')
                ->html($this->renderView('program/newProgramEmail.html.twig', ['program' => $program]));


        $mailer->send($email);
            
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**

     * @Route("/{slug}", methods={"GET"}, name="show")
     */
    public function show(Program $program, CategoryRepository $categoryRepository, ReviewRepository $reviewRepository): Response

    {


        if (!$program) {
            throw $this->createNotFoundException(
                'No program with id : ' . $program . ' found in program\'s table.'
            );
        }
        $seasons = $this->getDoctrine()
            ->getRepository(Season::class)
            ->findBy(
                ['program' => $program->getId()]
            );
        

        return $this->render('program/show.html.twig', [
            'program' => $program,
            'seasons' => $seasons,
            'categories' => $categoryRepository->findAll(),
            'reviews' => $reviewRepository->findBy(
                ['program' => $program->getId()]
            )
        ]);
    }

    /**

     * @Route("/{program}/seasons/{season}", name="season_show")
     */
    public function showSeason(Program $program, Season $season, CategoryRepository $categoryRepository): Response
    {

        $episodes = $season->getEpisodes();
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/{program}/season/{season}/episode/{episode}", name="episode_show")
     */
    public function showEpisode(Program $program, Season $season, Episode $episode, CategoryRepository $categoryRepository): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
            'categories' => $categoryRepository->findAll()
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
            'categories' => $categoryRepository->findAll()
        ]);
    }
}
