<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Influencer;
use App\Entity\Offer;

use App\Entity\User;
use App\Form\OfferType;
use App\Form\ApplicationType;
use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
use App\Repository\ApplicationRepository;

use App\Repository\OfferRepository;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * Class OfferController
 * @package App\Controller
 *
 * @Route("/offer", name="offer_")
 */

class OfferController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     */

    public function index(BrandRepository $brandRepository, InfluencerRepository $influencerRepository, ApplicationRepository $applicationRepository)
    {
        $repository = $this->getDoctrine()->getRepository(Offer::class);

        $user = $this->getUser();
        $brand = $brandRepository->findOneBy(['UserId' => $user]);

        $offers = $repository->findBy([], ['dateCreation' => 'DESC']);
        $influencer = $influencerRepository->findOneBy(['UserId' => $user]);

        $offerApplied = $applicationRepository->findApplicationAndInfluencer($influencer);

        $datenow = new \DateTime("now");

        return $this->render('offer/index.html.twig', [
            'offers' =>  $offers,
            'brand' => $brand,
            'datenow' => $datenow,
            'offerApplied' => $offerApplied
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     * @IsGranted("ROLE_MARQUE", statusCode=404, message="Vous n'avez pas accès à cette page!")
     */

    public function new(Request $request, BrandRepository $brandRepository)
    {

        $offer = new Offer();

        $user = $this->getUser();

        $brandId = $brandRepository->findOneBy(['UserId' => $user]);


        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setBrandId($brandId);
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();

            $this->addFlash('success', 'Création réussie');

            return $this->redirectToRoute('offer_index', ['id' => $offer->getId()]);
        }


        return $this->render('offer/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show($id, Offer $offer, BrandRepository $brandRepository, OfferRepository $offerRepository, influencerRepository $influencerRepository, applicationRepository $applicationRepository)
    {

        $offerId = $offerRepository->find($id);

        $this->denyAccessUnlessGranted('CAN_SHOW', $offerId, "Vous n'avez pas acces");

        $user = $this->getUser();
        $brand = $brandRepository->findOneBy(['UserId' => $user]);

        $influencer = $influencerRepository->findOneBy(['UserId' => $user]);

        $offerApplied = $applicationRepository->findApplicationAndInfluencer($influencer);

        $apply = $applicationRepository->findAll();


        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'brand' => $brand,
            'influencer' => $influencer,
            'offerApplied' => $offerApplied,
            'apply' => $apply
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit($id, Offer $offer, Request $request, OfferRepository $offerRepository)
    {
        $offerId = $offerRepository->find($id);

        $this->denyAccessUnlessGranted('CAN_EDIT', $offerId, "Vous n'avez pas acces");

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', 'Modification réussie');

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/apply", name="apply", methods={ "GET", "POST"})
     */
    public function apply(Offer $offer, Request $request, influencerRepository $influencerRepository, applicationRepository $applicationRepository, OfferRepository $offerRepository)
    {
        $form = $this->createForm(ApplicationType::class, $offer);
        $form->handleRequest($request);

        $user = $this->getUser();
        $influencer = $influencerRepository->findOneBy(['UserId' => $user]);

        $offerAppliedId = $applicationRepository->influencerApplyOfferId($influencer, $offer);

        if (!empty($offerAppliedId)) {
            return $this->redirectToRoute('offer_index');
        }

        $application = new Application();
        $offer->addApplication($application);
        $application->setOffer($offer);
        $application->addInfluencerId($influencer);
        $application->setStatus("pending");


        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            $this->addFlash('success', 'Postuler à l\'offre en cours');

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/apply.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'influencer' => $influencer
        ]);
    }



    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     * @IsGranted("ROLE_MARQUE", statusCode=404, message="Vous n'avez pas accès à cette page!")
     */
    public function delete($id, Offer $offer, $token, OfferRepository $offerRepository)
    {
        $offerId = $offerRepository->find($id);
        $this->denyAccessUnlessGranted('CAN_DELETE', $offerId, "Vous n'avez pas accès");


        if (!$this->isCsrfTokenValid('delete_offer' . $offer->getId(), $token)) {
            throw new \Exception('Token CSRF invalid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($offer);
        $offer->setBrandId(null);
        $em->flush();

        $this->addFlash('info', 'Suppression réussie');

        return $this->redirectToRoute('offer_index');
    }
}