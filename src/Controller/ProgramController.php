<?php

namespace App\Controller;

use App\Entity\Program;
use App\Entity\Season;
use App\Entity\Episode;
use App\Form\ProgramType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController

{
    /**

     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response

    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('program/index.html.twig', [

            'website' => 'Wild Séries', 'programs' => $programs

        ]);
    }
    /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request) : Response
    {
        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($program);
            $entityManager->flush();
            return $this->redirectToRoute('program_index');
        }

        return $this->render('program/new.html.twig', [
            "form" => $form->createView(),
        ]);

    }

    /**

     * @Route("/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="show")
     * @return Response
     */
    public function show(Program $program): Response

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

        return $this->render('program/show.html.twig', ['program' => $program, 'seasons' => $seasons]);
    }

    /**

     * @Route("/{program}/seasons/{season}", name="season_show")
     * @return Response
     */
    public function showSeason(Program $program, Season $season): Response
    {

        $episodes = $season->getEpisodes();
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episodes' => $episodes
        ]);
    }

    /**
     * @Route("/{program}/season/{season}/episode/{episode}", name="episode_show")
     * @return Response
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig',[
            'program' => $program,
            'season' => $season,
            'episode' => $episode
        ]);
    }
}
