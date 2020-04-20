<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
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
	 * @param SubCategoryRepository $subCategoryRepository
	 * @param TopicRepository $topicRepository
	 * @return Response
	 * 
	 * @Route("/categorie/{id}", name="category")
	 */
	public function index(Category $category, CategoryRepository $categoryRepository, SubCategoryRepository $subCategoryRepository, TopicRepository $topicRepository): Response
	{
		$categoryReturn = $categoryRepository->find($category);
		$subCategorys = $subCategoryRepository->findBy(array('id' => $category));
		$topics = $topicRepository->findBy(array(), array('createdAt' => 'DESC'));

		return $this->render('category/index.html.twig', [
			'category' => $categoryReturn,
			'subCategorys' => $subCategorys,
			'topics' => $topics
		]);
	}

	/**
	 * Display all categories
	 *
	 * @param CategoryRepository $categoryRepository
	 * @param SubCategoryRepository $subCategoryRepository
	 * @return Response
	 * 
	 * @Route("/categories", name="category_list")
	 */
	public function categoryList(CategoryRepository $categoryRepository, SubCategoryRepository $subCategoryRepository): Response
	{

		$categorysReturn = $categoryRepository->findAll();
		$subCategorys = $subCategoryRepository->findAll();

		return $this->render('category/list.html.twig', [
			'categorys' => $categorysReturn,
			'subCategorys' => $subCategorys
		]);
	}
}
