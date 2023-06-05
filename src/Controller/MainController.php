<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{

    #[Route('/', name: 'home')]
    public function index()
    {
        return new Response(content: '<h1> Bienvenue sur le tutoriel Symfony ! </h1>');
    }

    #[Route('/custom/{name?}', name: 'custom')]
    public function custom(Request $request)
    {
        $name = $request->get('name');
        if ($name == null)
        {
            return new Response(content: ' <h1> Welcome to the Custom Page. You are not logged in </h1>');
        }
        else
        {
        return new Response(content: ' <h1> Welcome ' . $name . ' to the Custom Page </h1>');
        }
    }

}
