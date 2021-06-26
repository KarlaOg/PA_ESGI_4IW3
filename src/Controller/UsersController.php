<?php

namespace App\Controller;


use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Offer;
use App\Form\BrandType;
use App\Entity\Influencer;
use App\Entity\Application;
use App\Form\InfluencerType;
use App\Form\ApplicationType;
use App\Form\EditProfileType;
use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ApplicationRepository;
use App\Repository\OfferRepository;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;


class UsersController extends AbstractController
{

    /**
     * @Route("/home", name="users_data")
     */

    public function usersData(OfferRepository $offerRepository, BrandRepository $brandRepository, ApplicationRepository $applicationRepository, InfluencerRepository $influencerRepository)
    {
        $user = $this->getUser();

        $brand = $brandRepository->findOneBy(['user' => $user]);
        $influencer = $influencerRepository->findOneBy(['user' => $user]);

        $validatedApps = array();
        $partnerships = array();

        $application = $applicationRepository->findApplicationAndInfluencer($influencer);

        // Recherche de partenariat pour un influenceur 
        foreach($application as $app){
            if( $app->getStatus() === "validated"){
                array_push($validatedApps, $app);
            }
        }

            if($brand){
                $brandId = $brand->getId();
                $offers = $offerRepository->findBy([
                    'brandId' => $brandId
                ]);
                //on recupere toutes les applications en lien avec la marque
                foreach ($offers as $offer) {
                    $allApps = $offer->getApplication();

                    foreach ($allApps as $app) {
                        if ($app->getStatus() === "validated") {
                            //recuperer l'influenceur qui a postulé l'offre
                            $influencer = $influencerRepository->findOneBy([
                                'id' => $app->getInfluencerId()->toArray()[0]
                            ]);
                            array_push($partnerships, $offer);
                        }
                    }
                }
            }
            
        $countOfferInfluencer = count($application);
        $countValidatedApps = count($validatedApps); // Influenceur
        $countPartnerships = count($partnerships); // Marque

        return $this->render('users/data.html.twig', [
            'brand' => $brand,
            'countOfferInfluencer' => $countOfferInfluencer,
            'countValidatedApps' => $countValidatedApps,
            'countPartnerships' => $countPartnerships,
        ]);
    }


    /**
     * @Route("/offers", name="users_offers")
     * @IsGranted("ROLE_INFLUENCEUR", statusCode=404, message="Vous n'avez pas accès à cette page!")
     */

    public function usersOffers(influencerRepository $influencerRepository, ApplicationRepository $applicationRepository, OfferRepository $offerRepository, BrandRepository $brandRepository)
    {
        $user = $this->getUser();
        $influencer = $influencerRepository->findOneBy(['user' => $user]);
        // GET ALL APPLICATIONS AS DOCTRINE PERSISTENT COLLECTION
        $allApplications = $influencerRepository->find($influencer)->getApplications();

        $offerApplied = $applicationRepository->findApplicationAndInfluencer($influencer);
        $offers = $offerRepository->findBy([], ['dateCreation' => 'DESC']);
        $brand = $brandRepository->findOneBy(['user' => $user]);

        return $this->render('users/offers.html.twig', [
            'applications' => $allApplications,
            'offerApplied' => $offerApplied,
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

            $this->addFlash('info', 'Modification effectué');
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
        $influcerInfos = $influencerRepository->findOneBy(['user' => $user]);
        $brandInfos = $brandRepository->findOneBy(['user' => $user]);

        if ($user->getRoles() == ["ROLE_INFLUENCEUR"] ||   $user->getRoles()[0] == "ROLE_INFLUENCEUR") {
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
                $this->addFlash('success', 'Mot de passe mis à jour avec succès');

                //return $this->redirectToRoute('users');
            } else {
                $this->addFlash('danger', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('users/editpass.html.twig');
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"DELETE"})
     */
    public function delete(Request $request, User $user): Response
    {

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte utilisateur a bien été supprimé !');

            $session = new Session();
            $session->invalidate();
        }

        return $this->redirectToRoute('app_logout');
    }
}
