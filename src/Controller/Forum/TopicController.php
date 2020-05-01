<?php

namespace App\Controller\Forum;

use App\Entity\Topic;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Form\NewTopicFormType;
use App\Controller\PokeController;
use App\Repository\TopicRepository;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends PokeController
{
	/**
	 * Add a new Topic
	 *
	 * @param integer $id
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 * 
	 * @Route("/topic/edition/{id<\d+>?0}", name="topic_edit")
	 */
	public function edit($id = 0, Request $request, EntityManagerInterface $entityManager, TopicRepository $topicRepository): Response
	{
		$user = $this->getUser();

		if ($user) {
			// New Topic
			if ($id == 0) {
				$topic = new Topic();

				// Topic exist
			} else {
				$topic = $topicRepository->find($id);
			}
			if ($topic) {
				if ($topic->getAuthor()) {
					// Is not author or not admin
					if (($user != $topic->getAuthor()) && (!$this->isGranted('ROLE_ADMIN'))) {
						return $this->redirectToRoute("topic_show", ["id" => $topic->getId()]);
					}
				}
			} else {
				return $this->redirectToRoute('home');
			}
		} else {
			return $this->redirectToRoute('home');
		}


		$form = $this->createForm(NewTopicFormType::class, $topic);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			if (!$topic->getAuthor()) {
				$topic->setAuthor($user);
			}
			$entityManager->persist($topic);

			$user->setPoints($user->getPoints() + 1);
			$entityManager->persist($user);

			$entityManager->flush();

			return $this->redirectToRoute("topic_show", ["id" => $topic->getId()]);
		}

		return $this->render('topic/new.html.twig', [
			'form' => $form->createView(),
		]);
	}

	/**
	 * Display a topic
	 *
	 * @param Topic $topic
	 * @param TopicRepository $topicRepository
	 * @param CommentRepository $commentRepository
	 * @param EntityManagerInterface $entityManager
	 * @param Request $request
	 * @return Response
	 * 
	 * @Route("/topic/{id}", name="topic_show")
	 */
	public function show(Topic $topic, TopicRepository $topicRepository, CommentRepository $commentRepository, EntityManagerInterface $entityManager, Request $request): Response
	{
		$user = $this->getUser();

		$topicSelected = $topicRepository->find($topic);
		$comments = $commentRepository->findBy(array('topic' => $topic));

		if ($topic->getIsActive() && $user) {
			$newComment = new Comment();

			$formComment = $this->createForm(CommentFormType::class, $newComment);
			$formComment->handleRequest($request);

			if ($formComment->isSubmitted() && $formComment->isValid()) {
				$newComment->setAuthor($user);
				$newComment->setTopic($topicSelected);
				$entityManager->persist($newComment);

				$user->setPoints($user->getPoints() + 1);
				$entityManager->persist($user);

				$entityManager->flush();

				return $this->redirect($request->getUri());
			}

			return $this->render('topic/show.html.twig', [
				'comments' => $comments,
				'topic' => $topicSelected,
				'commentForm' => $formComment->createView(),
			]);
		}

		return $this->render('topic/show.html.twig', [
			'comments' => $comments,
			'topic' => $topicSelected,
		]);
	}

	/**
	 * Remove a topic
	 *
	 * @param Topic $topic
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 * 
	 * @Route("/topic/supprimer/{id}", name="topic_remove")
	 */
	public function remove(Topic $topic, EntityManagerInterface $entityManager): Response
	{
		$user = $this->getUser();

		if ($topic) {
			// Is not author or not admin
			if (($user != $topic->getAuthor()) && (!$this->isGranted('ROLE_ADMIN'))) {
				return $this->redirectToRoute("topic_show", ["id" => $topic->getId()]);
			}
		} else {
			return $this->redirectToRoute('home');
		}
		
		$entityManager->remove($topic);
		$entityManager->flush();

		return $this->redirectToRoute('home');
	}
}
