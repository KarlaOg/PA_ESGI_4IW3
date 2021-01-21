<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserProfilController extends AbstractController
{
    /**
     * @Route("/user/profil/{$id}", name="user_profil", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('user_profil/index.html.twig', [
            'controller_name' => 'UserProfilController',
        ]);
    }


      /**
     * @Route("/show/{slug}", name="show", methods={"GET"})
     */
    public function show(Book $book): Response
    {
        return $this->render('back/book/show.html.twig', [
            'book' => $book
        ]);
    }
}
