<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Service\Pagination;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PostController extends AbstractController
{
    /**
     * @Route("/post/{page<\d+>?1}", name="post")
     */
    public function index(PostRepository $repo, Pagination $utils, $page)
    {
        $pagination = $utils->pagination($repo, 6, $page);

        return $this->render('post/index.html.twig', [
            'page' => $page,
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/post/new", name="post_create")
     * @Route("/post/{id}-{slug}/edit", name="post_edit")
     */
    public function form(Post $post = null, Request $request, ObjectManager $manager)
    {
        if(!$post) {
            $post = new Post();
        }

        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            foreach ($post->getImages() as $image){
                $image->setPost($post);
                $manager->persist($image);
            }

            $manager->persist($post);
            $manager->flush();

            $this->addFlash('success', "L'article <strong>{$post->getTitle()}</strong> a bien été ajouté !");

            return $this->redirectToRoute('post_show', [
                'id' => $post->getId(),
                'slug' => $post->getSlug()
            ]);
        }


        return $this->render('post/post.html.twig',[
            'form' => $form->createView(),
            'editMode' => $post->getId() !== null,
            'postTitle' =>  $post->getTitle()
        ]);
    }

    /**
     * @Route("/post/{id}-{slug}", name="post_show")
     */
    public function show(Post $post)
    {
        return $this->render('post/show.html.twig', [
            'post' => $post,
        ]);
    }
}
