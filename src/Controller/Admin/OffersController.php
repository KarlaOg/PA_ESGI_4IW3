<?php

namespace App\Controller\Admin;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\BrandRepository;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;



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
        $offer = $this->getDoctrine()->getRepository(Offer::class)->find($id);

        $comments = $offer->getComments();

        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $em->remove($comment);
            }
        }

        $em->remove($offer);
        $em->flush();

        $this->addFlash('Suppresion Offre', 'L\'offre a bien été supprimé !');

        return $this->redirectToRoute("offers_admin_list");
    }

    /**
     * @Route("admin/offre/commentaires/{id}", name="comments_admin_delete")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_commentaires($id): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $offer = $this->getDoctrine()->getRepository(Offer::class)->find($id);
        $comments = $offer->getComments();

        if (!empty($comments)) {
            foreach ($comments as $comment) {
                $em->remove($comment);
            }
        }

        $em->flush();

        $this->addFlash('Suppresion Commentaires', 'Tous les commentaires de l\'offre ont bien été supprimé !');

        return $this->redirectToRoute("offers_admin_list");
    }
}
