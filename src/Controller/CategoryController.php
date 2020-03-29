<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Repository\SubCategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id}", name="category")
     */
    public function index(Category $category,CategoryRepository $categoryRepository,SubCategoryRepository $subCategoryRepository)
    {

        $categoryReturn = $categoryRepository->find($category);
        $subCategorys = $subCategoryRepository->findBy(array('id' => $category));

        return $this->render('category/index.html.twig', [
            'category' => $categoryReturn,
            'subCategorys' => $subCategorys
        ]);

    }
}
