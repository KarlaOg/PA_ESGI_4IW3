<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Influencer;
use App\Form\EditUserType;
use App\Form\EditBrandType;
use App\Repository\UserRepository;
use App\Repository\BrandRepository;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApplicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * @Route("/admin/list/users", name="dashbaord_admin_list_users")
     */
    public function adminListUsers(UserRepository $userRepository)
    {
        $users = $userRepository->getBrandAndInfluencer();
        // dd($users);

        return $this->render('admin/list_users.html.twig', [
            'users' => $users,
        ]);
    }
    /**
     * @Route("/admin/edit/user/{id}", name="dashbaord_admin_edit_users")
     */
    public function editUser(UserRepository $userRepository, Request $request, $id,  EntityManagerInterface $em)
    {


        $user = $userRepository->find($id);


        $form = $this->createForm(EditUserType::class, $user);

        $form->handleRequest($request);


        $role = $user->getRoles();
        $admin = ["ROLE_ADMIN"];
        $merge = array_merge($role, $admin);
        if ($form->isSubmitted() && $form->isValid()) {

            if ($user->getIsAdmin(1)) {
                $user->setRoles($merge);
            } else {
                $key = array_search("ROLE_ADMIN", $merge);
                $user->setRoles(array_splice($merge, 0, $key));
            }
            $em->flush();
            return $this->redirectToRoute('dashbaord_admin_list_users');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user
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
