<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/category/{id]", name="category")
     */
    public function index(int $id,CategoryRepository $categoryRepository)
    {



        return $this->render('category/index.html.twig', [
            'category' => '$category',
        ]);
    }
}
