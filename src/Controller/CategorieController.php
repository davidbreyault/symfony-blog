<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/categories", name="categories")
     */
    public function index(Request $request): Response
    {
        $categories = $this->entityManager->getRepository(Categorie::class)->findAll();
        $form = $this->createForm(CategorieType::class, $categories);

        $form->handleRequest($request);
        
            if ($form->isSubmitted() && $form->isValid()) {
                $categorie = $form->get('categorie')->getData();
                $articles = $categorie->getArticles()->toArray();
                return $this->redirectToRoute('categories');
            }

        return $this->render('categorie/index.html.twig', [
            'form'      => $form->createView()
        ]);
    }
}
