<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\MicroPost;
use App\Entity\User;
use App\Entity\UserProfile;
use App\Repository\CommentRepository;
use App\Repository\MicroPostRepository;
use App\Repository\UserProfileRepository;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{
    private array $messages = [ 
        ['message' => 'Hello', 'created' => '2023/03/03'],
        ['message' => 'Hi', 'created' => '2023/02/02'],
        ['message' => 'Bye!', 'created' => '2021/05/12'],
    ];

    #[Route('/', name:'app_index')]
    public function index (MicroPostRepository $microPostRepository,CommentRepository $commentRepository):Response
    {
        // $post = new MicroPost();
        // $post->setTitle('Hello');
        // $post->setText('Hello');
        // $post->setCreated(new DateTime());
        $post=$microPostRepository->find(37);

        $comment = new Comment();
        $comment->setText('Hello');
        $comment->setPost($post);
        //$post->addComment($comment);
        $commentRepository->save($comment, true);
        
        
        /* $user= new User();
        $user->setEmail('emil@email.com');
        $user->setPassword('12345678');


        $profile= new UserProfile();
        $profile->setUser($user);
        $userProfileRepository->save($profile, true);
        $profile=$userProfileRepository->find(2);
        $userProfileRepository->remove($profile, true);*/

        return $this->render(
            'hello/index.html.twig',
           [
             'messages' => $this->messages,
             'limit' => 3
           ]
        );
    }

    #[Route('/messages/{id<\d+>}', name:'app_show_one')]
    public function showOne ($id)
    {
        return $this->render(
            'hello/show_one.html.twig',
            [
                'message' => $this->messages[$id]
            ]
        );
        // return new Response($this->messages[$id]);
    }

}