<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('username', TextType::class, [
				"label" => "Pseudo",
				"attr" => [
					"placeholder" => "DracaufeuDu93",
				],
			])
			->add('email', EmailType::class, [
				"attr" => [
					"placeholder" => "Dracaufeulebg@mail.fr",
				],
			])
			->add('name', TextType::class,[
                "label" => "Prénom"
            ])
			->add('lastname', TextType::class,[
                "label" => "Nom"
            ])
			->add('plainPassword', PasswordType::class, [
				// instead of being set onto the object directly,
				// this is read and encoded in the controller
                "label" => "Mot de passe",
				'mapped' => false,
				'constraints' => [
					new NotBlank([
						'message' => 'Veuillez entrer un mot de passe',
					]),
					new Length([
						'min' => 6,
						'minMessage' => 'Votre mot de passe doit faire au minimum {{ limit }} charactères',
						// max length allowed by Symfony for security reasons
						'max' => 4096,
					]),
				],
			])
			->add('agreeTerms', CheckboxType::class, [
                "label" => "Conditions générales",
                'mapped' => false,
				'constraints' => [
					new IsTrue([
						'message' => 'Veuillez accepter les conditions.',
					]),
				],
			]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => User::class,
		]);
	}
}
