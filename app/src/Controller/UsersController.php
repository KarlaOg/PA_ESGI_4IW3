<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Offer;
use App\Form\BrandType;
use App\Entity\Influencer;
use App\Form\InfluencerType;
use App\Form\EditProfileType;
use App\Repository\InfluencerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UsersController extends AbstractController
{

    /**
     * @Route("/users/data", name="users_data")
     */
    public function usersData()
    {
        $repository = $this->getDoctrine()->getRepository(Offer::class);
        $offers = $repository->findAll();


        return $this->render('users/data.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/users/profil/modifier", name="users_profil_modifier")
     */
    public function editProfile(Request $request)
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
     * @Route("/users/profil/create", name="users_profil_create")
     */
    public function create(Request $request, EntityManagerInterface $em,  FormFactoryInterface $factory, InfluencerRepository $influencerRepository)
    {
        if ($this->getUser()->getRoles() == ["ROLE_INFLUENCEUR"]) {

            $user = $this->getUser();
            $influencer = new Influencer();

            $form = $this->createForm(InfluencerType::class, $influencer);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $influencer->setUserId($user);

                $em->persist($influencer);
                $em->flush();

                return $this->redirectToRoute('users_data');
            }
            $formView = $form->createView();

            return $this->render('influencer/index.html.twig', [
                'formView' => $formView,
            ]);
        } else {
            $user = $this->getUser();
            $brand = new Brand();
            dd($brand);
            $form = $this->createForm(BrandType::class, $brand);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $brand->setUserId($user);

                $em->persist($brand);
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
     * @Route("/users/pass/modifier", name="users_pass_modifier")
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
