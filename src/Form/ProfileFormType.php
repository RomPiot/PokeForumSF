<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ProfileFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$oldPassword = $options["data"]->getPassword();

		$builder
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
				"required" => false,
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
			]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
