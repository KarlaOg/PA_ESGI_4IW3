<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Offer;
use App\Form\BrandType;
use App\Entity\Influencer;
use App\Form\InfluencerType;
use App\Form\EditProfileType;
use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UsersController extends AbstractController
{

    /**
     * @Route("/users/data", name="users_data")
     */
    public function usersData(BrandRepository $brandRepository)
    {
        $repository = $this->getDoctrine()->getRepository(Offer::class);
        $offers = $repository->findAll();

        $user = $this->getUser();

        $brand = $brandRepository->findOneBy(['UserId' => $user]);

        return $this->render('users/data.html.twig', [
            'offers' => $offers,
            'brand' => $brand
        ]);
    }


    /**
     * @Route("profile/edit", name="users_edit")
     */
    public function edit(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('green', 'Modification effectué');
            return $this->redirectToRoute('users_data');
        }

        return $this->render('users/editprofile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("profile/complete", name="users_complete")
     */
    public function complete(Request $request, EntityManagerInterface $em, InfluencerRepository $influencerRepository, BrandRepository $brandRepository)
    {
        $user = $this->getUser();
        $influcerInfos = $influencerRepository->findOneBy(['userId' => $user]);
        $brandInfos = $brandRepository->findOneBy(['UserId' => $user]);

        if ($user->getRoles() == ["ROLE_INFLUENCEUR"]) {
            $form = $this->createForm(InfluencerType::class, $influcerInfos);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();

                return $this->redirectToRoute('users_data');
            }

            $formView = $form->createView();

            return $this->render('influencer/index.html.twig', [
                'formView' => $formView,
            ]);
        } else {
            $form = $this->createForm(BrandType::class, $brandInfos);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $em->flush();

                return $this->redirectToRoute('users_data');
            }
            $formView = $form->createView();

            return $this->render('brand/index.html.twig', [
                'formView' => $formView,
            ]);
        }
    }


    /**
     * @Route("profile/change-password", name="users_pass_modifier")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();

            // On vérifie si les 2 mots de passe sont identiques
            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('blue', 'Mot de passe mis à jour avec succès');

                //return $this->redirectToRoute('users');
            } else {
                $this->addFlash('red', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('users/editpass.html.twig');
    }
}
