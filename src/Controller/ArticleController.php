<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/articles", name="articles")
     */
    public function index(): Response
    {
        $articles = $this->entityManager->getRepository(Article::class)->findAll();
        //dd($articles);
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("article/add", name="add_article")
     */
    public function add_article(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $article = $form->getData();
                $article->setCreatedAt(new \DateTimeImmutable());
                $article->setUser($this->getUser());

                $this->entityManager->persist($article);
                $this->entityManager->flush();
                $this->addFlash('success', 'Congrats, your article has been posted !');
                return $this->redirectToRoute('profile');
            }

        return $this->render('article/add.html.twig', [
            'form'     => $form->createView()
        ]);
    }

    /**
     * @Route("article/show/{id}", name="show_article")
     */
    public function show_article($id)
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        $comments = $article->getComments()->toArray();
        //dd($comments);

        return $this->render('article/show.html.twig', [
            'article'       => $article,
            'comments'      => $comments
        ]);
    }
}
