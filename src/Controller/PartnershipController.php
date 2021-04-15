<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PartnershipController extends AbstractController
{
    /**
     * @Route("/partnership", name="partnership")
     */
    public function index(BrandRepository $brandRepository)
    {
        $repository = $this->getDoctrine()->getRepository(Offer::class);

        $user = $this->getUser();
        $brand = $brandRepository->findOneBy(['UserId' => $user]);

        $offer = $repository->findBy([], ['dateCreation' => 'DESC']);

        return $this->render('offer/index.html.twig', [
            'offers' =>  $offer,
            'brand' => $brand
        ]);
    }


    /**
     * @Route("/my_partnership", name="my_partnership") 
    */
    public function my_partnership()
    {
        //voir mes offre ou j'ai validé la candidature
        return $this->render('partnership/index.html.twig');
    }





}
