<?php

namespace App\Controller;


use App\Entity\Comment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{


	/**
	 * Remove a comment
	 * 
	 * @Route("/commentaire/supprimer/{id}", name="comment_remove")
	 */
	public function remove(Comment $comment, EntityManagerInterface $entityManager)
	{

		if ($comment->getAuthor() == $this->getUser()) {
			$entityManager->remove($comment);
			$entityManager->flush();
		}
			
		return $this->redirectToRoute("topic_show", ["id" => $comment->getTopic()->getId()]);

	}

}
