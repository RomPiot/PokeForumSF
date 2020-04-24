<?php

namespace App\Form;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;


class NewTopicFormType extends AbstractType
{
	private $em;

	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$categoryRepository = $this->em->getRepository(Category::class);

		$mainCategories = $categoryRepository->findMainCategories();

		$builder
			->add('Category', EntityType::class, [
				"label" => "Catégorie",
				"choices" => $mainCategories,
				'class'       => 'App\Entity\Category',
				'placeholder' => 'Sélectionnez la catégorie',
				'constraints' => [
					new NotBlank([
						'message' => 'La catégorie est obligatoire',
					]),
				]
			]);

		$formModifier = function (FormInterface $form, Category $category = null) {

			if ($category != null) {

				$form->add('SubCategory', EntityType::class, [
					"label" => "Sous Catégorie",
					'placeholder' => 'Sélectionnez la sous-catégorie',
					'choices' => $category->getCategories(),
					'class'       => 'App\Entity\Category',
					'constraints' => [
						new NotBlank([
							'message' => 'La sous-catégorie est obligatoire',
						]),
					]
				]);

			} else {

				$form->add('SubCategory', EntityType::class, [
					"label" => "Sous Catégorie",
					'placeholder' => "Sélectionnez d'abord la catégorie",
					'choices' => [],
					'class'       => 'App\Entity\Category',
					'constraints' => [
						new NotBlank([
							'message' => 'La sous-catégorie est obligatoire',
							]),
						],
					'disabled' => true,
				]);
				
			}
		};

		$builder
			->add('title', null, [
				"label" => "Titre",
			])
			->add('content', null, [
				"label" => "Contenu"
			])
			->add('save', SubmitType::class, [
				'label' => "Ajouter"
			]);

		$builder->addEventListener(
			FormEvents::PRE_SET_DATA,
			function (FormEvent $event) use ($formModifier) {

				$data = $event->getData();
				$formModifier($event->getForm(), $data->getCategory());
			}
		);

		$builder->get('Category')->addEventListener(
			FormEvents::POST_SUBMIT,
			function (FormEvent $event) use ($formModifier) {
				$category = $event->getForm()->getData();
				$formModifier($event->getForm()->getParent(), $category);
			}
		);
	}
}
