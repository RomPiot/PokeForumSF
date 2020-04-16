<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
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
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
	private $token;

	public function __construct()
	{
		$this->token = "4fre84vrev8rec6r8zec!:fef4";
	}

	/**
	 * @Route("/profil/editer", name="user_profile_edit")
	 */
	public function edit(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $encoder)
	{
		$userConnected = $this->getUser();
		$user = $userRepository->find($userConnected);

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
	 * @Route("/profil/{id}", name="user_profile_show")
	 */
	public function show(User $user, UserRepository $userRepository)
	{
		return $this->render('user/index.html.twig', [
			'user' => $user,
		]);
	}

	/* Check if password is default */
	public function checkOldPassword($newPassword)
	{
		if ($newPassword == "default") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * To add a pokeball to all users who have less than 6 
	 * 
	 * @Route("/pokeball/add", name="user_add_pokeball")
	 */
	public function addPokeball(EntityManagerInterface $entityManager, Request $request)
	{
		// $tokenReceived = $request->request->get("token");

		// if ($tokenReceived = $this->token) {

		$updatePokeball = $entityManager->createQuery('update App\Entity\User u set u.pokeball = u.pokeball + 1 where u.pokeball < 6');

		$updatePokeball->execute();

		return new Response('Allright !');
		// } else {
		// 	return new Response("You haven't access !");
		// }
	}


	/**
	 * To remove a pokeball to one user
	 * 
	 * @Route("/pokeball/remove", name="user_remove_pokeball")
	 */
	public function removePokeball(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager)
	{
		// $tokenReceived = $request->request->get("token");

		// Not working 
		// $userId = $request->request->get("user_id");

		$jsonPostRequest = \json_decode($request->getContent());
		$userId = $jsonPostRequest->user_id;

		// if ($tokenReceived = $this->token) {

		$user = $userRepository->find($userId);

		$nbPokeballUser = $user->getPokeball();
		if ($nbPokeballUser > 0) {

			$removePokeball = $nbPokeballUser - 1;

			$user->setPokeball($removePokeball);
			$entityManager->persist($user);
			$entityManager->flush();

			return new Response('Remove a pokeball. Now : ' . $user->getPokeball());
		} else {
			return new Response("User haven't pokeball");
		}
		// } else {
		// 	return new Response("You haven't access !");
		// }
	}
}
