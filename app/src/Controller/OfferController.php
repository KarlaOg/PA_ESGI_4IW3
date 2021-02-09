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

    public function index(): Response
    {
        $repository = $this->getDoctrine()->getRepository(Offer::class);

        $offer = $repository->findBy([
            'status' => 'Libre',
        ]);
        //  if( $offer->setStatus($this->status = "En attente de validation");){
        return $this->render('offer/index.html.twig', [
            //'offers' => $offerRepository->findBy(array(), array('status' => 'Libre')),
            'offers' =>  $offer,
        ]);
        //  }
    }


    /**

     * @Route("/new", name="new", methods={"GET", "POST"})

     */

    public function new(Request $request)
    {

        $offer = new Offer();

        $dateStart = $offer->getDateStart();
        $dateEnd = $offer->getDateEnd();
        $user = $this->getUser();
        if (array_search("ROLE_MARQUE", $user->getRoles()) !== false) {
            $brand = new Brand();
            $brand->setUserId($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($brand);
        }

        $dateStart = $offer->getDateStart();
        $dateEnd = $offer->getDateEnd();

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
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
    public function show(Offer $offer): Response
    {

        return $this->render('offer/show.html.twig', [
            'offer' => $offer
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
     * @Route("/apply/{id}/", name="apply", methods={ "GET", "POST"})
     *  Postuler à une offre
     * 
     * 
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
