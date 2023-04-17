<?php

namespace App\Controller;

use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LikeController extends AbstractController
{
    /**
    * this funcion is useful to add a like to a post
    */
    #[Route('/like/{id}', name: 'app_like')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function like(
        MicroPost $post,
        MicroPostRepository $microPostRepository,
        Request $request
    ): Response
    {
        //get the current user
        $currentUser=$this->getUser();
        //current user ad a like to the post
        $post->addLikedBy($currentUser);
        //save the post with the repository
        $microPostRepository->save($post, true);

        //render the template
        return $this->redirect($request->headers->get('referer'));
    }

    /**
    * this funcion is useful to remove like from a post
    */
    #[Route('/unlike/{id}', name: 'app_unlike')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function unlike(
        Micropost $post,
        MicroPostRepository $microPostRepository,
        Request $request
    ): Response
    {
        //get the current user
        $currentUser=$this->getUser();
        //current user remove like from the post
        $post->removeLikedBy($currentUser);
        //save the post with the repository
        $microPostRepository->save($post, true);

         //render the template
        return $this->redirect($request->headers->get('referer'));
    }
}
