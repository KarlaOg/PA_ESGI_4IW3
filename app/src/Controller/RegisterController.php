<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Influencer;
use App\Form\RegisterType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


/**
 * @Route("/register", name="register_")
 */
class RegisterController extends AbstractController
{

    /**
     * @param $id
     *
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            if (array_search("ROLE_INFLUENCEUR", $user->getRoles()) !== false) {
                $influencer = new Influencer();
                $influencer->setUserId($user);


                $em = $this->getDoctrine()->getManager();
                $em->persist($influencer);
            } else if (array_search("ROLE_MARQUE", $user->getRoles()) !== false) {
                $brand = new Brand();
                $brand->setUserId($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($brand);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Inscription réussie !");
            return $this->redirectToRoute('app_login');
        }

        // afficher le formulaire s'il n'est pas déjà rempli
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
