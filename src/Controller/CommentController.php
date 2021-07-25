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
}
