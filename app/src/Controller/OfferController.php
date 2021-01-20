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

        return $this->render('offer/new_offer.html.twig', [

            'offer' => $offerRepository->findAll()

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
 

            var_dump('fait');
 

            //return $this->redirectToRoute('back_book_show', ['id' => $book->getId()]);

            return $this->render('offer/new_offer.html.twig', [

                'form' => $form->createView()

            ]);

        }

        var_dump('non fait');

        return $this->render('offer/new_offer.html.twig', [

            'form' => $form->createView()

        ]);

    }

  }

 ?>