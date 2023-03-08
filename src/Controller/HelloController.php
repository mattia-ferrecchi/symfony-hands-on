<?php

namespace App\Controller;

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

    #[Route('/{limit<\d+>?3}', name:'app_index')]
    public function index (int $limit)
    {
        return $this->render(
            'hello/index.html.twig',
           [
             'messages' => $this->messages,
             'limit' => $limit
           ]
        );
        // return new Response(implode(',', array_slice ($this->messages, 0, $limit)));
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