<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="user_profile")
     */
    public function index(User $user, UserRepository $userRepository)
    {		
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
