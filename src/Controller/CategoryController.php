<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
	/**
	 * The category's details page
	 *
	 * @param Category $category
	 * @param CategoryRepository $categoryRepository
	 * @param TopicRepository $topicRepository
	 * @return Response
	 * 
	 * @Route("/categorie/{id}", name="category")
	 */
	public function index(Category $category, CategoryRepository $categoryRepository, TopicRepository $topicRepository): Response
	{
		$category = $categoryRepository->find($category);
		// $subCategories = $subCategoryRepository->findBy(array('id' => $category));
		$subCategories = $category->getCategories();
		$topics = $topicRepository->findBy(array(), array('createdAt' => 'DESC'));

		return $this->render('category/index.html.twig', [
			'category' => $category,
			'subCategories' => $subCategories,
			'topics' => $topics
		]);
	}

	/**
	 * Display all categories
	 *
	 * @param CategoryRepository $categoryRepository
	 * @return Response
	 * 
	 * @Route("/categories", name="category_list")
	 */
	public function categoryList(CategoryRepository $categoryRepository): Response
	{
		$categories = $categoryRepository->findMainCategories();
		$subCategories = $categoryRepository->findsubCategories();

		return $this->render('category/list.html.twig', [
			'categories' => $categories,
			'subCategories' => $subCategories
		]);
	}
}
