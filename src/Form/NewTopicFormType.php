<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class NewTopicFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Category', EntityType::class, [
                'class'       => 'App\Entity\Category',
                'placeholder' => '',
            ])
        ;

        // ça dois venir de la | je t'es remis la configuration d'origine pour que ça soit plus simple à comprendre
        $formModifier = function (FormInterface $form, Category $category = null) {
            $subCategory = null === $category ? [] : $category->getCategory();

            // Dans mes test seul le 'child' 'category' fonctionne -> mais on en peut pas l'afficher qua ça fait doublon avec l'autre
            $form->add('SubCategory', EntityType::class, [
                'class' => 'App\Entity\Category',
                'placeholder' => '',
                // on les récupère à partir de la catégorie séléctionner dans le formulaire en Ajax
                'choices' => $subCategory,
            ]);
        };

        $builder
            ->add('title')
            ->add('content')
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