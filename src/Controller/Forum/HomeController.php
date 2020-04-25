<?php

namespace App\Controller\Forum;

use App\Controller\PokeController;
use App\Repository\TopicRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends PokeController
{
	/**
	 * The website's homepage page
	 *
	 * @param CategoryRepository $categoryRepository
	 * @param TopicRepository $topicRepository
	 * @return Response
	 * 
	 * @Route("/", name="home")
	 */
	public function index(CategoryRepository $categoryRepository, TopicRepository $topicRepository): Response
	{
		$topics = $topicRepository->findBy(array(), array('createdAt' => 'DESC'));
		$categories = $categoryRepository->findMainCategories();

		return $this->render('home/index.html.twig', [
			'categories' => $categories,
			'topics' => $topics
		]);
	}
}
