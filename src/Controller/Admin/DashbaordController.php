<?php

namespace App\Controller\Admin;

use App\Repository\UserRepository;
use App\Repository\OfferRepository;
use App\Repository\ApplicationRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DashbaordController extends AbstractController
{
    /**
     * @Route("/admin/dashbaord", name="dashbaord_admin_dashbaord")
     */
    public function index(UserRepository $userRepository, ApplicationRepository $applicationRepository, OfferRepository $offerRepository)
    {
        $users = $userRepository->findAll();
        $offers = $offerRepository->findAll();
        $applications = $applicationRepository->findAll();

        $countUsers = count($users);
        $countOffers = count($offers);
        $countApplications = count($applications);

        return $this->render('admin/index.html.twig', [
            'countUsers' => $countUsers,
            'countOffers' => $countOffers,
            'countApplications' => $countApplications
        ]);
    }
}
