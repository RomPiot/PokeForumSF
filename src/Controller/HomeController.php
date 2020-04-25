<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
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
