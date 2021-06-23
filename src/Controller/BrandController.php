<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BrandRepository;
use App\Repository\OfferRepository;
use App\Repository\InfluencerRepository;
use App\Repository\ApplicationRepository;
use App\Entity\Brand;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class BrandController extends AbstractController
{
    /**
     * @Route("/marque", name="brand")
     */
    public function index(): Response
    {
        return $this->render('brand/index.html.twig', [
            'controller_name' => 'BrandController',
        ]);
    }

    /**
     * @Route("/marques", name="all_brands")
     * @Security("is_granted('ROLE_INFLUENCEUR') or is_granted('ROLE_ADMIN')")
     */
    public function brands_list(BrandRepository $brandRepository): Response
    {
        return $this->render('brand/list.html.twig', [
            'brands' => $brandRepository->findAll()
        ]);
    }

    /**
     * @Route("marque/{username}", name="brand_show", methods={"GET"})
     * @Security("is_granted('ROLE_INFLUENCEUR') or is_granted('ROLE_ADMIN')")
     */
    public function show(Brand $brand, OfferRepository $offerRepository, BrandRepository $brandRepository, influencerRepository $influencerRepository, applicationRepository $applicationRepository): Response
    {
        $brandId = $brandRepository->findOneBy(['username' => $brand->getUsername()]);

        $offers = $offerRepository->findBy(['brandId' => $brandId->getId()]);

        $user = $this->getUser();

        $influencer = $influencerRepository->findOneBy(['user' => $user]);

        $offerApplied = $applicationRepository->findApplicationAndInfluencer($influencer);

        $apply = $applicationRepository->findAll();


        return $this->render('brand/show.html.twig', [
            'brand' => $brand,
            'offers' => $offers,
            'influencer' => $influencer,
            'offerApplied' => $offerApplied,
            'apply' => $apply
        ]);
    }
}
