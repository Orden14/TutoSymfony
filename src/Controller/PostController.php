<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;
use App\Services\FileUploader;

#[Route('/post', name: 'post')]
class PostController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function show(PostRepository $postRespository) : Response
    {
        $posts = $postRespository->findAll();

        return $this->render('post/index.html.twig', ['posts' => $posts]);
    }

    #[Route('/show/{id}', name: 'show')]
    public function showPost(Post $post) : Response
    {
        // ici (en commentaire) exemple d'utilisation des repository pour faire des requetes
        // $post = $postRepository->findPostWithCategory($id); 
        // dump($post);
        // retrouver la fonction dans postRepository

        return $this->render('post/show.html.twig', ['post' => $post]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager, FileUploader $fileUploader) : Response
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $file = $request->files->get('post')['attachment'];
            if ($file)
            {
                $filename = $fileUploader->uploadFile($file);                

                $post->setImage($filename);
                $entityManager->persist($post);
                $entityManager->flush();
            }
            return $this->redirect($this->generateUrl('postindex'));
        }


        // return a response
        return $this->render('post/create.html.twig', ['form' => $form->createView()]);

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
