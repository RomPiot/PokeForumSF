<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserAdminController extends EasyAdminController
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;
	private $userRepository;

	public function __construct(UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
	{
		$this->encoder = $encoder;
		$this->userRepository = $userRepository;
	}

	public function persistUserEntity($user)
	{
		$this->updatePassword($user);
		parent::persistEntity($user);
	}

	public function updateUserEntity($user)
	{
		$this->updatePassword($user);
		parent::updateEntity($user);
	}

	public function updatePassword(User $user)
	{
		$passwordEncoded = $this->encoder->encodePassword($user, $user->getPassword());

		$oldPassword = $this->userRepository->findPassword($user->getId())["password"];

		if ($user->getPassword() == "default") {
			$user->setPassword($oldPassword);
		} else {
			$user->setPassword($passwordEncoded);
		}
	}
}
