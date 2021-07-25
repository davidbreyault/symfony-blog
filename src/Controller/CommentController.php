<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Article;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    /**
     * @Route("comments", name="my_comments")
     */
    public function index(): Response
    {
        $user = $this->getUser();
        $comments = $user->getComments()->toArray();
        //dd($comments);

        return $this->render('comment/my-comments.html.twig', [
            'comments'      => $comments
        ]);
    }

    /**
     * @Route("/comment/add/{id}", name="add_comment")
     */
    public function add_comment(Article $article, Request $request, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment;
        $form = $this->createForm(CommentType::class, $comment);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUser($this->getUser());
            $comment->setArticle($article);

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show_article', ['id' => $article->getId()]);
        }

        return $this->render('comment/add.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/remove-comment/{id}", name="remove_comment")
     */
    public function remove_comment($id)
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);
        $article_id = $comment->getArticle()->getId();

        $this->entityManager->persist($comment);
        $this->entityManager->remove($comment);
        $this->entityManager->flush();
        $this->addFlash('success', 'Your comment has been deleted !');

        return $this->redirectToRoute('show_article', ['id' => $article_id]);
    }

    /**
     * @Route("/update-comment/{id}", name="update_comment")
     */
    public function update_comment(Request $request, EntityManagerInterface $entityManager, $id): Response
    {
        $comment = $this->entityManager->getRepository(Comment::class)->find($id);
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment = $form->getData();
            $comment->setCreatedAt(new \DateTimeImmutable());
            $comment->setUser($this->getUser());
            $article_id = $comment->getArticle()->getId();

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('show_article', ['id' => $article_id]);
        }

        return $this->render('comment/update.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
