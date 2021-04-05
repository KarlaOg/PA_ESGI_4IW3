<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Offer;
use App\Repository\UserRepository;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $userLogged = $this->getUser();

        if ($userLogged) {
            return $this->redirectToRoute('users_data');
        }

        $repository = $this->getDoctrine()->getRepository(Offer::class);
        $offers = $repository->findAll();
        return $this->render('home/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/admin/list", name="home_admin")
     */
    public function adminList(UserRepository $userRepository)
    {


        $users =  $userRepository->findAll();

        // foreach ($users as $key) {
        // array_values($users);


        return $this->render('home/admin/index.html.twig', [
            'users' => $users
        ]);
    }
}
