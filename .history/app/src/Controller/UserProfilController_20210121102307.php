<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;


class UserProfilController extends AbstractController
{
    /**
     * @Route("/user/profil/{$id}", name="user_profil", methods={"GET"})
     */
    public function index(User $user): Response
    {
        return $this->render('user_profil/index.html.twig', [
          
            'id' => $id
        ]);
    }

}
