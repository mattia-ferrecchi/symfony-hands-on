<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use DateTime;
use PhpParser\Node\Stmt\Label;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MicroPostController extends AbstractController
{
    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $microPostRepository): Response
    {
        $posts = $microPostRepository->findAll();
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts
        ]);
    }

    #[Route('/micro-post/{id}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

    #[Route('/micro-post/add', name: 'app_micro_post_add', priority:2)]
    public function add(Request $request, MicroPostRepository $microPostRepository): Response
    {
        $micropost = new MicroPost();
        $form = $this->createFormBuilder($micropost)
            ->add ('title')
            ->add ('text')
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post = $form->getData();
            $post->setCreated(new DateTime());
            $microPostRepository->save($post, true);
            $this->addFlash('success', 'you micro post has been added');
            return $this->redirectToRoute('app_micro_post');
        };
        
        return $this->renderform('micro_post/add.html.twig',[
            'form'=>$form
        ]);
    }

    #[Route('/micro-post/{id}/edit', name: 'app_micro_post_edit')]
    public function edit(MicroPost $post, Request $request, MicroPostRepository $microPostRepository): Response
    {
        $form = $this->createFormBuilder($post)
            ->add ('title')
            ->add ('text')
            ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $post = $form->getData();
            $microPostRepository->save($post, true);
            $this->addFlash('success', 'you micro post has been updated');
            return $this->redirectToRoute('app_micro_post');
        };
        
        return $this->renderform('micro_post/edit.html.twig',[
            'form'=>$form,
            'post'=>$post
        ]);
    }

    /**
     * this funcion is useful to delete a post
     */
    #[Route('/micro-post/{id}/delete', name: 'app_micro_post_delete')]
    public function delete(MicroPost $post, MicroPostRepository $microPostRepository): Response
    {
        //get post title
        $title=$post->getTitle();
        //delete post using related repository, pass true as second argument to flush delete to database
        $microPostRepository->remove($post, true);
        //pass a message to the flash store to say post has been deleted
        $this->addFlash('success', "you micro post $title has been deleted");
        //after deleting the post return to the posts list
        return $this->redirectToRoute('app_micro_post');
    }
}   
