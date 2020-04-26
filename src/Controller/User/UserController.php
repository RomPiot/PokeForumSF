<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Controller\PokeController;
use App\Form\ProfileFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends PokeController
{
	/**
	 * User palmares
	 *
	 * @param UserRepository $userRepository
	 * @return Response
	 * 
	 * @Route("/dresseurs", name="user_palmares")
	 */
	public function index(UserRepository $userRepository): Response
	{
		$usersArray = $userRepository->findTopList();

		$users = [];

		foreach ($usersArray as $user) {
			\array_push($users, $user[0]);
		}

		// \dd($users);
		return $this->render('user/index.html.twig', [
			"users" => $users,
		]);
	}

	/**
	 * The user's page edition
	 *
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

		$form = $this->createForm(ProfileFormType::class, $user);
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
	 * @Route("/dresseur/{id}", name="user_profile_show")
	 */
	public function show(User $user): Response
	{
		return $this->render('user/show.html.twig', [
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
