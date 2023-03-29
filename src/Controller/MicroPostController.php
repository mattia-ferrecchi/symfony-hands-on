<?php

namespace App\Controller;

use App\Entity\Comment;
use DateTime;
use App\Entity\MicroPost;
use App\Form\CommentType;
use PhpParser\Node\Stmt\Label;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MicroPostController extends AbstractController
{
        /**
     * this funcion is useful to show the post list
     */

    #[Route('/micro-post', name: 'app_micro_post')]
    public function index(MicroPostRepository $microPostRepository): Response
    {
        //get an array with all the posts
        $posts = $microPostRepository->findAllWithComments();
        //render the template
        return $this->render('micro_post/index.html.twig', [
            'posts' => $posts
        ]);
    }

        /**
     * this funcion is useful to show a single post
     */
    #[Route('/micro-post/{id}', name: 'app_micro_post_show')]
    public function showOne(MicroPost $post): Response
    {
        return $this->render('micro_post/show.html.twig', [
            'post' => $post,
        ]);
    }

        /**
     * this funcion is useful to add a post
     */
    #[Route('/micro-post/add', name: 'app_micro_post_add', priority:2)]
    public function add(Request $request, MicroPostRepository $microPostRepository): Response
    {
        //create new micropost
        $micropost = new MicroPost();
        //create a form with title and text
        $form = $this->createFormBuilder($micropost)
            ->add ('title')
            ->add ('text')
            ->getForm();
        $form->handleRequest($request);

        //check if the post is submitted and valid
        if($form->isSubmitted() && $form->isValid()){
            //get the post from form
            $post = $form->getData();
            //set the date
            $post->setCreated(new DateTime());
            //save post using related repository, pass true as second argument to flush add to database
            $microPostRepository->save($post, true);
            //pass a message to the flash store to say post has been added
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
        //create a form with title and text
        $form = $this->createFormBuilder($post)
            ->add ('title')
            ->add ('text')
            ->getForm();
        $form->handleRequest($request);

        //check if the post is submitted and valid
        if($form->isSubmitted() && $form->isValid()){
            // get the post from the form
            $post = $form->getData();
            //save post using related repository, pass true as second argument to flush add to database
            $microPostRepository->save($post, true);
             //pass a message to the flash store to say post has been edited
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

    /**
     * this funcion is useful to add a comment to post
     */
    #[Route('/micro-post/{id}/comment', name: 'app_micro_post_comment')]
    public function addComment(MicroPost $post, Request $request, CommentRepository $commentRepository): Response
    {
        //create a form for the new comment
        $form = $this->createForm(CommentType::class, new Comment());
        $form->handleRequest($request);

        //check if the comment is submitted and valid
        if($form->isSubmitted() && $form->isValid()){
            //get comment from form
            $comment = $form->getData();
            //associate the comment to the post
            $comment->setPost($post);
            //save comment using related repository, pass true as second argument to flush add to database
            $commentRepository->save($comment, true);
            //pass a message to the flash store to say comment has been added
            $this->addFlash('success', 'you comment has been added');
            //after adding the comment return to the post page
            return $this->redirectToRoute(
                'app_micro_post_show',
                ['id'=>$post->getId()]
            );
        };
        
        return $this->renderform('micro_post/comment.html.twig',[
            'form'=>$form,
            'post'=>$post,
        ]);

    }

}   
