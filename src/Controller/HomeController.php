<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Offer;
use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
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
     * @Route("/admin/list/users", name="admin_list_users")
     */
    public function adminListUsers(UserRepository $userRepository, BrandRepository $brandRepository, InfluencerRepository $influencerRepository)
    {
        $users = $userRepository->getBrandAndInfluencer();

        return $this->render('home/admin/list_users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/list/offers", name="admin_list_offers")
     */
    public function adminListOffers(OfferRepository $offerRepository)
    {

        $offers =  $offerRepository->findAll();
        // dd($offers);
        return $this->render('home/admin/list_offers.html.twig', [
            'offers' => $offers
        ]);
    }
}
