<?php

namespace App\Controller;
 

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;

use App\Repository\OfferRepository;

use App\Entity\Offer;

use App\Form\OfferType;
 

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

    public function index(OfferRepository $offerRepository): Response

    {
        return $this->render('offer/index.html.twig', [

            'offers' => $offerRepository->findBy(array(), array('name' => 'ASC'))

        ]);

    }
 

    /**

     * @Route("/new", name="new", methods={"GET", "POST"})

     */

    public function new(Request $request)

    {

        $offer = new Offer();

        $form = $this->createForm(OfferType::class, $offer);
 

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())

        { 
            $em = $this->getDoctrine()->getManager();

            $em->persist($offer);

            $em->flush();
 
            $this->addFlash('success', 'Création réussie');
 

            //return $this->redirectToRoute('back_book_show', ['id' => $book->getId()]);

            return $this->render('offer/new.html.twig', [

                'form' => $form->createView()

            ]);

        }

        // var_dump('non fait');

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
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('success', 'Modification réussie');

            return $this->redirectToRoute('offer_edit', ['id' => $offer->getId()]);
        }

        return $this->render('offer/edit.html.twig', [
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
            throw new Exception('Token CSRF invalid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($offer);
        $em->flush();

        $this->addFlash('success', 'Suppression réussie');

        return $this->redirectToRoute('offer_index');
    }

  }

 ?>