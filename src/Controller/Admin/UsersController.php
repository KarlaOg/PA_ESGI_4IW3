<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\BrandType;
use App\Form\EditUserType;
use App\Form\InfluencerType;
use App\Repository\UserRepository;
use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UsersController extends AbstractController
{

    /**
     * @Route("/admin/list/users", name="users_admin_list")
     */
    public function list(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();
        // dd($users);

        return $this->render('admin/list_users.html.twig', [
            'users' => $users,
        ]);
    }
    /**
     * @Route("/admin/edit/user/{id}", name="users_admin_edit")
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
            return $this->redirectToRoute('users_admin_list');
        }

        return $this->render('admin/edit_user.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/edit/brand/{id}", name="users_admin_edit_brand")
     */
    public function editBrand(BrandRepository $brandRepository, Request $request, $id,  EntityManagerInterface $em)
    {

        $brand = $brandRepository->find($id);

        $form = $this->createForm(BrandType::class, $brand);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();
            return $this->redirectToRoute('users_admin_list');
        }

        return $this->render('admin/edit_brand.html.twig', [
            'form' => $form->createView(),
            'brand' => $brand,
        ]);
    }

    /**
     * @Route("/admin/edit/influencer/{id}", name="users_admin_edit_influencer")
     */
    public function editInfluencer(Request $request, $id,  EntityManagerInterface $em, InfluencerRepository $influencerRepository)
    {

        $influencer = $influencerRepository->find($id);

        $form = $this->createForm(InfluencerType::class, $influencer);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $em->flush();
            return $this->redirectToRoute('users_admin_list');
        }

        return $this->render('admin/edit_influencer.html.twig', [
            'form' => $form->createView(),
            'influencer' => $influencer
        ]);
    }


    /**
     * @Route("admin/user/{id}", name="users_admin_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository(User::class)->find($id);

        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute("users_admin_list");
    }
}
