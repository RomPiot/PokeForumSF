<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('content', TextType::class, [
			'label' => 'Commentaire'
		])
		->add('save', SubmitType::class, [
			'label' => "Ajouter"
		])
		->getForm();
        ;
    }
}
