<?php

namespace App\Controller;


use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Brand;
use App\Entity\Offer;

use App\Form\OfferType;
use App\Entity\Application;
use App\Form\ApplicationType;
use App\Repository\OfferRepository;
use App\Repository\BrandRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;


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

        $brand = $brandRepository->findOneBy(['UserId' => $user]);

        $offer = $repository->findBy([
            'status' => 'Libre',
        ]);
        return $this->render('offer/index.html.twig', [
            'offers' =>  $offer,
            'brand' => $brand
        ]);
    }


    /**
     * @Route("/new", name="new", methods={"GET", "POST"})
     */

    public function new(Request $request, BrandRepository $brandRepository)
    {

        $offer = new Offer();

        $dateStart = $offer->getDateStart();
        $dateEnd = $offer->getDateEnd();

        $user = $this->getUser();

        $brandId = $brandRepository->findOneBy(['UserId' => $user]);

        if (array_search("ROLE_MARQUE", $user->getRoles()) !== false) {
        }
        $dateStart = $offer->getDateStart();
        $dateEnd = $offer->getDateEnd();

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        $user = $this->getUser();

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setBrandId($brandId);
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();

            $this->addFlash('green', 'Création réussie');

            return $this->redirectToRoute('offer_index', ['id' => $offer->getId()]);
        }


        return $this->render('offer/new.html.twig', [

            'form' => $form->createView()

        ]);
    }

    /**
     * @Route("/show/{id}", name="show", methods={"GET"})
     */
    public function show(Offer $offer, BrandRepository $brandRepository)
    {
        $user = $this->getUser();

        $brand = $brandRepository->findOneBy(['UserId' => $user]);

        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'brand' => $brand
        ]);
    }


    /**
     * @Route("/edit/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit(Offer $offer, Request $request)
    {
        $dateStart = $offer->getDateStart();
        $dateEnd = $offer->getDateEnd();
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('blue', 'Modification réussie');

            return $this->redirectToRoute('offer_edit', ['id' => $offer->getId()]);
        }

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/apply/{id}/", name="apply", methods={ "GET", "POST"})     * 
     */
    public function apply(Offer $offer, Request $request)
    {
        $form = $this->createForm(ApplicationType::class, $offer);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $offer->setStatus($this->status = "En attente de validation");

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('blue', 'Postuler à l\'offre en cours');

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/apply.html.twig', [
            'offer' => $offer,
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/delete/{id}/{token}", name="delete", methods={"GET"})
     */
    public function delete(Offer $offer, $token)
    {
        if (!$this->isCsrfTokenValid('delete_offer' . $offer->getId(), $token)) {
            throw new \Exception('Token CSRF invalid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($offer);
        $em->flush();

        $this->addFlash('red', 'Suppression réussie');

        return $this->redirectToRoute('offer_index');
    }
}
