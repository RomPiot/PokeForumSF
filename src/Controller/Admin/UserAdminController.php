<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\EasyAdminController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserAdminController extends EasyAdminController
{
	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $encoder;

	public function __construct(UserPasswordEncoderInterface $encoder)
	{
		$this->encoder = $encoder;
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
	}
}
