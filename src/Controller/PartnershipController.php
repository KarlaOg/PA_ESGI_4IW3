<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OfferRepository;
use App\Entity\Application;
use App\Repository\ApplicationRepository;
use App\Repository\InfluencerRepository;

class PartnershipController extends AbstractController
{
    /**
     * @Route("/partnership", name="partnership")
     */
    public function index(BrandRepository $brandRepository)
    {
    }


    /**
     * @Route("/my_partnership", name="my_partnership") 
    */
    public function my_partnership(OfferRepository $offerRepository, influencerRepository $influencerRepository)
    {
        $brandId = $this->getUser();

        //on recupere tout les offres lié à la brandId
        $offers = $offerRepository->findby([
            'brandId' => $brandId
        ]);

        $partnerships = array();
        $influencers = array();
        //on recupere toutes les applications en lien avec la marque
        foreach($offers as $offer) {
            $applications = $offer->getApplication();
            foreach($applications as $application) {
                $influencer = $influencerRepository->findOneby([
                    'id' => $application->getInfluencerId()->toArray()[0]
                ]);
                if (strcmp($application->getStatus(), "validated") == 0) {
                    array_push($partnerships, $offer);
                    array_push($influencers, $influencer);
                }
            }
        }

        return $this->render('partnership/index.html.twig', [
            'partnerships' => $partnerships,
            'influencers' => $influencers
        ]);
    }
}
