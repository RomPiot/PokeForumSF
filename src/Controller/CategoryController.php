<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends PokeController
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
	public function show(Category $category, CategoryRepository $categoryRepository, TopicRepository $topicRepository): Response
	{
		$category = $categoryRepository->find($category);
		
		// If is a main category
		if ($category->getParentCategory() == null) {
			// get all subcategories
			$subCategories = $category->getCategories();
			
			// TODO 
			// get all topics in subcategories related to main category
			$topics = $topicRepository->findBy(["category" => $category], array('createdAt' => 'DESC'));
			
			return $this->render('category/show.html.twig', [
				'category' => $category,
				'subCategories' => $subCategories,
				'topics' => $topics,
				'mainCategory' => true
				]);
			} else {

			// get all topics in subcategories related to a main category
			$topics = $topicRepository->findBy(["subCategory" => $category], array('createdAt' => 'DESC'));
			
			return $this->render('category/show.html.twig', [
				'category' => $category,
				'topics' => $topics,
				'mainCategory' => false
				]);
			}
	}

	/**
	 * Display all categories
	 *
	 * @param CategoryRepository $categoryRepository
	 * @return Response
	 * 
	 * @Route("/categories", name="category_list")
	 */
	public function index(CategoryRepository $categoryRepository): Response
	{
		$categories = $categoryRepository->findMainCategories();
		$subCategories = $categoryRepository->findsubCategories();

		return $this->render('category/index.html.twig', [
			'categories' => $categories,
			'subCategories' => $subCategories
		]);
	}
}
