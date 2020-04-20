<?php

namespace App\Controller;

use App\Entity\SubCategory;
use App\Repository\SubCategoryRepository;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SubCatgoryController extends AbstractController
{
    /**
     * @Route("/sousCategorie/{id}", name="subCategory")
     */
    public function index(SubCategory $subCategory,SubCategoryRepository $subCategoryRepository,TopicRepository $topicRepository)
    {
        $SubCategorySelected = $subCategoryRepository->find($subCategory);
		$topics = $topicRepository->findBy(array('id' => $SubCategorySelected));

        return $this->render('subCategory/index.html.twig', [
            'topics' => $topics,
            'subCategory' => $SubCategorySelected
        ]);
    }
}
