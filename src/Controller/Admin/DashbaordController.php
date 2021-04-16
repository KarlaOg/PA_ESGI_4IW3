<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashbaordController extends AbstractController
{
    /**
     * @Route("/admin/dashbaord", name="dashbaord_admin_dashbaord")
     */
    public function index(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        $countUsers = count($users);

        return $this->render('admin_dashbaord/index.html.twig', [
            'countUsers' => $countUsers
        ]);
    }

    /**
     * @Route("/admin/list/users", name="dashbaord_admin_list_users")
     */
    public function adminListUsers(UserRepository $userRepository)
    {
        $users = $userRepository->getBrandAndInfluencer();
        // dd($users);

        // foreach ($users as $key) {
        //     dd($key->getBrands()->getUserId());
        // }

        return $this->render('admin_dashbaord/list_users.html.twig', [
            'users' => $users,
        ]);
    }


    /**
     * @Route("/admin/list/offers", name="dashbaord_admin_list_offers")
     */
    public function adminListOffers(OfferRepository $offerRepository)
    {
        $offers =  $offerRepository->findAll();

        return $this->render('admin_dashbaord/list_offers.html.twig', [
            'offers' => $offers
        ]);
    }
}
