<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Controller\PokeController;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends PokeController
{
	/**
	 * The user's page edition
	 *
	 * @param UserRepository $userRepository
	 * @param Request $request
	 * @param EntityManagerInterface $entityManager
	 * @param UserPasswordEncoderInterface $encoder
	 * @return Response
	 * 
	 * @Route("/profil/editer", name="user_profile_edit")
	 */
	public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder): Response
	{
		$user = $this->getUser();

		$oldPassword = $user->getPassword();

		$form = $this->createFormBuilder($user)
			->add('username')
			->add('description')
			->add('name')
			->add('lastname')
			->add('avatar')
			->add('email', EmailType::class)
			->add('password', PasswordType::class, [
				"required" => false,
				"empty_data" => "default",
			])
			->add('oldPassword', HiddenType::class, [
				"data" => $oldPassword,
			])
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

		$newPassword = $user->getPassword();

		if ($form->isSubmitted() && $form->isValid()) {
			$passwordEncoded = $encoder->encodePassword($user, $newPassword);
			$samePassword = $this->checkOldPassword($newPassword);

			if ($samePassword == true) {
				$user->setPassword($oldPassword);
			} else {
				$user->setPassword($passwordEncoded);
			}

			$entityManager->persist($user);
			$entityManager->flush();

			return $this->redirectToRoute("user_profile_show", ["id" => $user->getId()]);
		}

		return $this->render('user/edit.html.twig', [
			'userForm' => $form->createView(),
		]);
	}

	/**
	 * Display user's details
	 *
	 * @param User $user
	 * @return Response
	 * 
	 * @Route("/profil/{id}", name="user_profile_show")
	 */
	public function show(User $user): Response
	{
		return $this->render('user/index.html.twig', [
			'user' => $user,
		]);
	}

	/**
	 * Check if password is default
	 *
	 * @param string $newPassword
	 * @return boolean
	 */
	public function checkOldPassword(string $newPassword): bool
	{
		if ($newPassword == "default") {
			return true;
		} else {
			return false;
		}
	}
}
