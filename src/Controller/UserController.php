<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="user_profile_show")
     */
    public function show(User $user, UserRepository $userRepository)
    {		
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
	}
	
	/**
     * @Route("/profil/{id}", name="user_profile_edit")
     */
    public function edit(User $user, UserRepository $userRepository)
    {		
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }
}
