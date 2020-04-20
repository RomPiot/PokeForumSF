<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Topic;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TopicController extends AbstractController
{
	/**
	 * @Route("/topic/{id}", name="topic_show")
	 */
	public function show(Topic $topic, TopicRepository $topicRepository)
	{
		$topicSelected = $topicRepository->find($topic);

		return $this->render('topic/show.html.twig', [
			'topic' => $topicSelected,
		]);
	}

	/**
	 * @Route("/newTopic", name="new_topic")
	 */
	public function new(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager)
	{
		$userConnected = $this->getUser();
		$user = $userRepository->find($userConnected);
		$topic = new Topic();

		$form = $this->createFormBuilder($topic)
			->add('title')
			->add('content')
			->add('id', EntityType::class, [
				'class' => Category::class,
				'label' => 'Catégorie'
			])
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {

			$topic->setAuthor($user);
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute("new_topic");
		}

		return $this->render('topic/new.html.twig', [
			'topicForm' => $form->createView(),
		]);
	}
}
