<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Influencer;
use App\Repository\UserRepository;
use App\Repository\BrandRepository;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Request;
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
        dd($users);
        $countUsers = count($users);

        return $this->render('admin/index.html.twig', [
            'countUsers' => $countUsers
        ]);
    }

    /**
     * @Route("/admin/list/users", name="dashbaord_admin_list_users")
     */
    public function adminListUsers(UserRepository $userRepository)
    {
        $users = $userRepository->getBrandAndInfluencer();
        return $this->render('admin/list_users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("admin/user/{id}", name="dashbaord_admin_user_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository(User::class)->find($id);

        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute("dashbaord_admin_list_users");
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
