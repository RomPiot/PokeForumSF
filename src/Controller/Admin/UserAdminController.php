<?php

namespace App\Controller\Admin;

use App\Controller\UserController;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserAdminController extends EasyAdminController
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;
	private $userController;

	public function __construct(UserPasswordEncoderInterface $encoder, UserController $userController)
	{
		$this->encoder = $encoder;
		$this->userController = $userController;
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

		$Pwdlength = \strlen($user->getPassword());
		$passwordEncoded = $this->encoder->encodePassword($user, $user->getPassword());
		
		if ($Pwdlength == 60) {
			$user->setPassword($user->getPassword());
		} else {
			$user->setPassword($passwordEncoded);
		}

		// $newPassword = $user->getPassword();
		// $oldPassword = $user->getOldPassword();

		// // \dump($oldPassword);
		// // \dd($newPassword);

		// $passwordEncoded = $this->encoder->encodePassword($user, $user->getPassword());

		// $samePassword = $this->userController->checkOldPassword($newPassword);

		// if ($samePassword == true) {
		// 	$user->setPassword($oldPassword);
		// } else {
		// 	$user->setPassword($passwordEncoded);
		// }
	}
}
