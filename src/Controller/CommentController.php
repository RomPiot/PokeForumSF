<?php

namespace App\Controller;


use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{


	/**
	 * Remove a comment
	 * 
	 * @param Comment $comment
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	  
	 * @Route("/commentaire/supprimer/{id}", name="comment_remove")
	 */
	public function remove(Comment $comment, EntityManagerInterface $entityManager): Response
	{

		if ($comment->getAuthor() == $this->getUser()) {
			$entityManager->remove($comment);
			$entityManager->flush();
		}

		return $this->redirectToRoute("topic_show", ["id" => $comment->getTopic()->getId()]);
	}

	/**
	 * Edit a comment
	 * 
	 * @Route("/commentaire/edition/", name="comment_edit")
	 */
	public function edit(EntityManagerInterface $entityManager, CommentRepository $commentRepository, Request $request)
	{
		$user = $this->getUser();

		$jsonPostRequest = \json_decode($request->getContent());

		$commentId = $jsonPostRequest->comment_id;
		$commentContent = $jsonPostRequest->comment_content;

		if ($user) {
			$comment = $commentRepository->find($commentId);
			if ($user != $comment->getAuthor()) {
				return $this->redirectToRoute('home');
			}
		} else {
			return $this->redirectToRoute('home');
		}

		$comment->setContent($commentContent);

		$entityManager->persist($comment);
		$entityManager->flush();

		return $this->json(true);
	}
}
