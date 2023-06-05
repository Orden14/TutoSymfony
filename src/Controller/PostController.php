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

    #[Route('/', name: 'show')]
    public function show(PostRepository $postRespository)
    {
        $posts = $postRespository->findAll();

        return $this->render('post/index.html.twig', ['posts' => $posts]);
    }

    #[Route('/create/{msg?}', name: 'create')]
    public function create(EntityManagerInterface $entityManager, Request $request) : Response
    {
        $msg = $request->get('msg');

        $post = new Post();

        if ($msg == null)
        {
            $post->setTitle(' Undefined title ');
        }
        else
        {
            $post->setTitle(htmlspecialchars($msg));
        }

        $entityManager->persist($post);
        $entityManager->flush();

        // return a response
        return new Response(' Hey ! The post was created ! <br> The post was : ' . $post->getTitle());

    }

}
