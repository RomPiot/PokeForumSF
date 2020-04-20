<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Topic;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TopicController extends AbstractController
{
    /**
     * @Route("/topic/{id}", name="topic_show")
     */
    public function show(Topic $topic, TopicRepository $topicRepository, CommentRepository $commentRepository, EntityManagerInterface $entityManager, Request $request, UserRepository $userRepository)
    {

        $userConnected = $this->getUser();
        $user = $userRepository->find($userConnected);

        $topicSelected = $topicRepository->find($topic);
        $comments = $commentRepository->findBy(array('topic'=>$topic));

        $newComment = new Comment();

        $form = $this->createFormBuilder($newComment)
            ->add('content',TextType::class ,[
                'label'=>'Commentaire'
            ])
            ->add('save', SubmitType::class, [
                'label' => "Ajouter"
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $newComment->setAuthor($user);
            $newComment->setTopic($topicSelected);
            $entityManager->persist($newComment);
            $entityManager->flush();

            return $this->redirect($request->getUri());
        }




        return $this->render('topic/show.html.twig', [
            'comments'=>$comments,
            'topic' => $topicSelected,
            'commentForm' => $form->createView(),

        ]);
    }

    /**
     * @Route("/newTopic", name="new_topic")
     */
    public function new(UserRepository $userRepository, Request $request, EntityManagerInterface $entityManager)
    {
        $userConnected = $this->getUser();
        $user = $userRepository->find($userConnected);
        $topic = new Topic();

        $form = $this->createFormBuilder($topic)
            ->add('title')
            ->add('content')
            ->add('id',EntityType::class,[
                'class' => Category::class,
                'label' => 'Catégorie'
                ] )
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $topic->setAuthor($user);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("new_topic");
        }

        return $this->render('topic/new.html.twig', [
            'topicForm' => $form->createView(),
        ]);



    }
}
