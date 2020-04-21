<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Topic;
use App\Form\NewTopicFormType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Proxies\__CG__\App\Entity\SubCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TopicController extends AbstractController
{
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
		$userConnected = $this->getUser();
		$user = $userRepository->find($userConnected);

		$allUsers = $userRepository->findAll();

		$topicSelected = $topicRepository->find($topic);
		$comments = $commentRepository->findBy(array('topic' => $topic));

		if ($topic->getIsActive()) {
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
	 * Add a new Topic
	 *
	 * @param UserRepository $userRepository
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @return Response
	 * 
	 * @Route("/newTopic", name="new_topic")
	 */
	public function new(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager): Response
	{
		$userConnected = $this->getUser();
		$user = $userRepository->find($userConnected);
		$topic = new Topic();

		$form = $this->createForm(NewTopicFormType::class, $topic);
		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$topic->setAuthor($user);
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute("home");
		}

		return $this->render('topic/new.html.twig', [
			'form' => $form->createView(),
		]);
	}
}
