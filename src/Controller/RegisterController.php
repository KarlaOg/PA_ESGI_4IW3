<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Brand;
use App\Form\BrandType;
use App\Entity\Influencer;
use App\Form\RegisterType;
use App\Form\InfluencerType;
use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


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
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);
        $userLogged = $this->getUser();

        if ($userLogged) {
            return $this->redirectToRoute('users_data');
        }

        if ($form->isSubmitted() && $form->isValid()) {
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
                ->content('Bienvenue ' . $user->getLastname() . ' chez LIKEY et Merci pour votre confiance.');

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

            $guard->authenticateUserAndHandleSuccess($user, $request, $login, 'main');

            return $this->redirectToRoute('register_complete');
        }

        // afficher le formulaire s'il n'est pas déjà rempli
        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/complete", name="complete")
     */
    public function complete(Request $request, EntityManagerInterface $em, InfluencerRepository $influencerRepository, BrandRepository $brandRepository)
    {
        $user = $this->getUser();

        $influcerInfos = $influencerRepository->findOneBy(['user' => $user]);
        $brandInfos = $brandRepository->findOneBy(['user' => $user]);


        if ($influcerInfos) {
            if ($influcerInfos->getName() !== null) {
                return $this->redirectToRoute('users_data');
            }
        }
        if ($brandInfos) {
            if ($brandInfos->getName() !== null) {
                return $this->redirectToRoute('users_data');
            }
        }
        if ($user !== null) {
            if ($user->getRoles() == ["ROLE_INFLUENCEUR"] ||   $user->getRoles()[0] == "ROLE_INFLUENCEUR") {
                $form = $this->createForm(InfluencerType::class, $influcerInfos);
                $form->handleRequest($request);

                if ($form->isSubmitted() && $form->isValid()) {
                    $em->flush();

                    return $this->redirectToRoute('users_data');
                }

                $formView = $form->createView();

                return $this->render('register/complete_influencer.html.twig', [
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

                return $this->render('register/complete_brand.html.twig', [
                    'formView' => $formView,
                ]);
            }

            return $this->redirectToRoute('users_data');
        }
    }
}