<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Form\CategoryType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController

{
    /**
     * @Route("/", name="index")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
             'categories' => $categories

        ]);
    }

    /**
     * The controller for the category add form
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request, CategoryRepository $categoryRepository) : Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
            return $this->redirectToRoute('category_index');
        }

        return $this->render('category/new.html.twig', [
            "form" => $form->createView(),
            'categories' => $categoryRepository->findAll()
        ]);

    }

    /**
     * @Route("/{categoryName}", name="show")
     * @return Response
     */
    public function show(string $categoryName, CategoryRepository $categoryRepository): Response
    {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => $categoryName]);

            if(!$category) {
                throw $this->createNotFoundException(
                    'No category with name : '.$categoryName.' found in category\'s table.'
                );
            }
       $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(
                ['Category' => $category->getId()],
                ['Category' => 'DESC'],
            );
        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'categoryName' => $categoryName,
            'categories' => $categoryRepository->findAll()
        ]);
    }

}
