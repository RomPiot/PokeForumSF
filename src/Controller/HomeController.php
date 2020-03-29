<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\TopicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(CategoryRepository $categoryRepository,TopicRepository $topicRepository)
    {
        $topics = $topicRepository->findBy(array(), array('createdAt' => 'DESC'));
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'topics' =>$topics
        ]);
    }


}


