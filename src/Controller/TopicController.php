<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Topic;
use App\Form\NewTopicFormType;
use App\Repository\CommentRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TopicController extends AbstractController
{
	/**
	 * Add a new Topic
	 *
	 * @param UserRepository $userRepository
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 * 
	 * @Route("/topic/edition/{id<\d+>?0}", name="topic_edit")
	 */
	public function edit($id = 0, Request $request, EntityManagerInterface $entityManager, TopicRepository $topicRepository): Response
	{
		$user = $this->getUser();
		
		if (!$user) {
			return $this->redirectToRoute('home');
		}

		if ($id == 0) {
			$topic = new Topic();
		} else {
			$topic = $topicRepository->find($id);
		}

		$form = $this->createForm(NewTopicFormType::class, $topic);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$topic->setAuthor($user);
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
	 * @param UserRepository $userRepository
	 * @return Response
	 * 
	 * @Route("/topic/{id}", name="topic_show")
	 */
	public function show(Topic $topic, TopicRepository $topicRepository, CommentRepository $commentRepository, EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository): Response
	{
		$user = $this->getUser();

		$allUsers = $userRepository->findAll();

		$topicSelected = $topicRepository->find($topic);
		$comments = $commentRepository->findBy(array('topic' => $topic));

		if ($topic->getIsActive() && $user) {
			$newComment = new Comment();

			$form = $this->createFormBuilder($newComment)
				->add('content', TextType::class, [
					'label' => 'Commentaire'
				])
				->add('save', SubmitType::class, [
					'label' => "Ajouter"
				])
				->getForm();

			$form->handleRequest($request);

			if ($form->isSubmitted() && $form->isValid()) {
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
				'commentForm' => $form->createView(),
				'users' => $allUsers
			]);
		}

		return $this->render('topic/show.html.twig', [
			'comments' => $comments,
			'topic' => $topicSelected,
			'users' => $allUsers
		]);
	}

	
	/**
	 * Remove a topic
	 * 
	 * @Route("/topic/supprimer/{id}", name="topic_remove")
	 */
	public function remove(Topic $topic, EntityManagerInterface $entityManager)
	{
		$entityManager->remove($topic);
		$entityManager->flush();
		
		return $this->redirectToRoute('home');
	}
}
