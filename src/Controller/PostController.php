<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

#[Route('/post', name: 'post')]
class PostController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function show(PostRepository $postRespository) : Response
    {
        $posts = $postRespository->findAll();

        return $this->render('post/index.html.twig', ['posts' => $posts]);
    }

    #[Route('/{id}', name: 'show')]
    public function showPost(Post $post) : Response
    {
        dump($post); die;
        return $this->render('post/show.html.twig', ['post' => $post]);
    }

    #[Route('/create/{msg}', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $msg = $request->get('msg');

        $post = new Post();

        $post->setTitle(htmlspecialchars($msg));

        $entityManager->persist($post);
        $entityManager->flush();

        // return a response
        return $this->redirect($this->generateUrl(('postindex')));

    }

    #[Route('/delete/{id}', name: 'delete')]
    public function remove(Post $post, EntityManagerInterface $entityManager) : Response
    {
        $entityManager->remove($post);
        $entityManager->flush();

        $this->addFlash('success', 'Post was removed');

        return $this->redirect($this->generateUrl(('postindex')));
    }

}
