<?php

namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $repo)
    {
        $limit = 6;

        $post = $repo->findBy([], ['id' => 'DESC'], $limit);
        $total = count($repo->findAll());

        return $this->render('home/index.html.twig', [
            'lastPost' => $post,
            'nbrPost' => $total
        ]);
    }
}
