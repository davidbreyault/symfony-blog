<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Article;
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
                $categories = $form->getData();
                $categorie = $categories['categorie'];
                $categorie->getTitle();
                $articles = $this->entityManager->getRepository(Article::class)->findBy(['categorie'=>$categorie]);

                //return $this->redirectToRoute('categories');
                return $this->render('categorie/index.html.twig', [
                    'form'          => $form->createView(),
                    'articles'      => $articles
                ]);
            }

        return $this->render('categorie/index.html.twig', [
            'form'      => $form->createView()
        ]);
    }
}
