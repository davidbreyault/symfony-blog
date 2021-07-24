<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\ArticleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("/profile", name="profile")
     */
    public function index(): Response
    {

        $user = $this->getUser();
        //dd($user);
        $articles = $user->getArticle()->toArray();

        return $this->render('profile/index.html.twig', [
            'user'              => $user,
            'articles'          => $articles
        ]);
    }

    /**
     * @Route("/profile/remove-article/{id}", name="remove_article")
     */
    public function remove_article($id)
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);

        $this->entityManager->persist($article);
        $this->entityManager->remove($article);
        $this->entityManager->flush();
        $this->addFlash('success', 'Your article has been deleted !');

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/profile/update-article/{id}", name="update_article")
     */
    public function update_article(Request $request, $id)
    {
        $article = $this->entityManager->getRepository(Article::class)->find($id);
        //dd($article);
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $article = $form->getData();
                $article->setCreatedAt(new \DateTimeImmutable());
                $article->setUser($this->getUser());

                $this->entityManager->persist($article);
                $this->entityManager->flush();
                $this->addFlash('success', 'Congrats, your article has been updated !');
                return $this->redirectToRoute('profile');
            }

        return $this->render('article/update.html.twig', ['form'     => $form->createView()]);
    }
}
