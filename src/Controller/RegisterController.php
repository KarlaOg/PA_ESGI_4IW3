<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Influencer;
use App\Form\RegisterType;
use App\Form\BrandType;
use App\Form\InfluencerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Doctrine\ORM\EntityManagerInterface;
use App\Security\LoginFormAuthenticator;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;


/**
 * @Route("/register", name="register_")
 */
class RegisterController extends AbstractController
{

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
    public function createAction(Request $request, EntityManagerInterface $manager, NotifierInterface $notifier, LoginFormAuthenticator $login, GuardAuthenticatorHandler $guard)
    {
        $user = new User();
        $brand = new Brand();
        $influencer = new Influencer();
        $formU = $this->createForm(RegisterType::class, $user);
        $formB = $this->createForm(BrandType::class, $brand);
        $formI = $this->createForm(InfluencerType::class, $influencer);
        $formU->handleRequest($request);
        $formB->handleRequest($request);
        $formI->handleRequest($request);
        $userLogged = $this->getUser();

        if ($userLogged) {
            return $this->redirectToRoute('users_data');
        }

        if ($formU->isSubmitted() && $formU->isValid()) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));

            if (array_search("ROLE_INFLUENCEUR", $user->getRoles()) !== false) {
                $influencer = new Influencer();
                $influencer->setUser($user);
                $em = $this->getDoctrine()->getManager();
                $em->persist($influencer);

            } else if (array_search("ROLE_MARQUE", $user->getRoles()) !== false) {
                $brand = new Brand();
                $brand->setUser($user);

                $em = $this->getDoctrine()->getManager();
                $em->persist($brand);
            }
           
            $userEmail = $user->getEmail();
            $notification = (new Notification('Confirmation d\'inscription', ['email']))
                ->content('Bienvenue '. $user->getLastname() . ' chez LIKEY et Merci pour votre confiance.');

            // user recoit le mail
            $recipient = new Recipient(
                $userEmail
            );
            // Send the notification to the recipient
            $notifier->send($notification, $recipient);


            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash("success", "Inscription réussie !");
            return $this->redirectToRoute('app_login');
        }
        // afficher le formulaire s'il n'est pas déjà rempli
        return $this->render('register/index.html.twig', [
            'formUser' => $formU->createView(),
            'formBrand' => $formB->createView(),
            'formInfluencer' => $formI->createView()
        ]);
    }
    private function createInfluencerAction($request, $user)
    {
        $influencer = new Influencer();
        $influencer->setUser($user);
        $influencer->setName('testdespuislecontroller');
        //--------------
        $formI = $this->createForm(InfluencerType::class, $influencer);
        $formI->handleRequest($request);
        //--------------
        $em = $this->getDoctrine()->getManager();
        $em->persist($influencer);
    }
    private function createBrandAction($request, $user)
    {
        $brand = new Brand();
        $brand->setUser($user);
        $brand->setName('testdespuislecontroller');
        //--------------
        $formB = $this->createForm(BrandType::class, $brand);
        $formB->handleRequest($request);
        //--------------
        $em = $this->getDoctrine()->getManager();
        $em->persist($brand);
    }
}
