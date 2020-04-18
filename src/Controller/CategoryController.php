<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categorie/{id}", name="category")
     */
    public function index(Category $category,CategoryRepository $categoryRepository,SubCategoryRepository $subCategoryRepository,TopicRepository $topicRepository)
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
     * @Route("/categories", name="category_list")
     */
    public function categoryList(CategoryRepository $categoryRepository,SubCategoryRepository $subCategoryRepository)
    {

        $categorysReturn = $categoryRepository->findAll();
        $subCategorys = $subCategoryRepository->findAll();

        return $this->render('category/list.html.twig', [
            'categorys' => $categorysReturn,
            'subCategorys' => $subCategorys
        ]);

    }




}
