<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\MicroPostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
    * this funcion is useful to show all the posts written by a single user
    */
    #[Route('/profile/{id}', name: 'app_profile')]
    public function show(
        User $user,
        MicroPostRepository $microPostRepository
    ): Response
    {
        //get an array with all the posts written by the user
        $posts= $microPostRepository->findAllByAuthor($user);

        return $this->render('profile/show.html.twig', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    /**
    * this funcion is useful to show people that user follows
    */
    #[Route('/profile/{id}/follows', name: 'app_profile_follows')]
    public function follows(User $user): Response
    {
        return $this->render('profile/follows.html.twig', [
            'user' => $user,
        ]);
    }

    /**
    * this funcion is useful to show user followers
    */
    #[Route('/profile/{id}/followers', name: 'app_profile_followers')]
    public function followers(User $user): Response
    {
        return $this->render('profile/followers.html.twig', [
            'user' => $user,
        ]);
    }
}
