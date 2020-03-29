<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
	/**
	 * @Route("/profil/editer", name="user_profile_edit")
	 */
	public function edit(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager)
	{
		$userConnected = $this->getUser();
		$user = $userRepository->find($userConnected);

		$form = $this->createFormBuilder($user)
			->add('avatar')
			->add('username')
			->add('description')
			->add('name')
			->add('lastname')
			->add('email', EmailType::class)
			->add('password')
			->add('birthday', DateType::class, [
				// renders it as a single text box
				'widget' => 'single_text',
			])
			->add('gender', ChoiceType::class, [
				'choices'  => [
					'Homme' => 'man',
					'Femme' => 'woman',
					'Autre' => 'other',
				]
			])
			->add('save', SubmitType::class, [
				'label' => "Enregistrer"
			])
			->getForm();

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute("user_profile_show", ["id" => $user->getId()]);
		}

		return $this->render('user/edit.html.twig', [
			'userForm' => $form->createView(),
		]);
	}

	/**
	 * @Route("/profil/{id}", name="user_profile_show")
	 */
	public function show(User $user, UserRepository $userRepository)
	{
		return $this->render('user/index.html.twig', [
			'user' => $user,
		]);
	}
}
