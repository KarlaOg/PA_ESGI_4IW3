<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\BrandRepository;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OffersController extends AbstractController
{

    /**
     * @Route("/admin/liste/offres", name="offers_admin_list")
     */
    public function list(OfferRepository $offerRepository, BrandRepository $brandRepository)
    {
        $brands = $brandRepository->findAll();

        return $this->render('admin/list_offers.html.twig', [
            'brands' => $brands,
        ]);
    }

    /**
     * @Route("admin/offre/editer/{id}", name="offers_admin_edit")
     */
    public function edit($id, Offer $offer, Request $request, OfferRepository $offerRepository)
    {

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', 'Modification réussie');

            return $this->redirectToRoute('offers_admin_list');
        }

        return $this->render('admin/edit_offer.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("admin/offre/{id}", name="offer_admin_delete")
     */
    public function delete($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $this->getDoctrine()->getRepository(Offer::class)->find($id);

        $em->remove($entity);
        $em->flush();

        return $this->redirectToRoute("offers_admin_list");
    }
}
