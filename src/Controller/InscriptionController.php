<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\InscriptionType;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


/**
 * @Route("/inscription", name="inscription_")
 */
class InscriptionController extends AbstractController
{

    // /**
    //  * @Route("/", name="index")
    //  */
    // public function index(): Response
    // {
    //     return $this->render('inscription/index.html.twig', [
    //         'controller_name' => 'InscriptionController',
    //     ]);
    // }

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


    /**
     * @Route("/", name="index")
     * @param Request $request
     * @return Response
     */
    public function createAction(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(InscriptionType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $user->setPassword(
            //     $this->passwordEncoder->encodePassword($user, $form->get("password")->getData())
            // );
            $user->setToken($this->generateToken());
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash("success", "Inscription réussie !");
            return $this->redirectToRoute('connexion/index.html.twig');
        }

        // afficher le formulaire s'il n'est pas déjà rempli
        return $this->render('inscription/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @return string
     * @throws \Exception
     */
    private function generateToken()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}
