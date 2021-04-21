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

    public function index(BrandRepository $brandRepository)
    {
        $repository = $this->getDoctrine()->getRepository(Offer::class);

        $user = $this->getUser();
        $brand = $brandRepository->findOneBy(['user' => $user]);

        $offer = $repository->findBy([], ['dateCreation' => 'DESC']);

        return $this->render('offer/index.html.twig', [
            'offers' =>  $offer,
            'brand' => $brand
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

        $brandId = $brandRepository->findOneBy(['user' => $user]);


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
        $brand = $brandRepository->findOneBy(['user' => $user]);

        $influencer = $influencerRepository->findOneBy(['user' => $user]);
        // $application = $applicationRepository->find($influencer);


        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'brand' => $brand,

        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     * @IsGranted("ROLE_MARQUE", statusCode=404, message="Vous n'avez pas accès à cette page!")
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
     * @Route("/apply/{id}/", name="apply", methods={ "GET", "POST"})
     */
    public function apply(Offer $offer, Request $request, influencerRepository $influencerRepository)
    {
        $form = $this->createForm(ApplicationType::class, $offer);
        $form->handleRequest($request);

        $user = $this->getUser();

        $influencer = $influencerRepository->findOneBy(['user' => $user]);


        if ($form->isSubmitted() && $form->isValid()) {
            $application = new Application();
            $offer->addApplication($application);
            $application->setOffer($offer);
            $application->addInfluencerId($influencer);
            $application->setStatus("pending");

            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();

            $this->addFlash('success', 'Postuler à l\'offre en cours');

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/apply.html.twig', [
            'offer' => $offer,
            'form' => $form->createView()
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
