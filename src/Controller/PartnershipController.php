<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OfferRepository;
use App\Entity\Application;
use App\Repository\ApplicationRepository;
use App\Repository\InfluencerRepository;
use App\Repository\BrandRepository;

class PartnershipController extends AbstractController
{
    /**
     * @Route("/my_partnership", name="my_partnership") 
    */
    public function my_partnership(OfferRepository $offerRepository, ApplicationRepository $applicationRepository, influencerRepository $influencerRepository, brandRepository $brandRepository)
    {
        $user = $this->getUser();
        $brand = $brandRepository->findOneBy(['user' => $user]);
        $influencerId = $influencerRepository->findOneBy(['user' => $user ]);
        $partnerships = array();
        $collaborators = array();

        if ($brand) {
            //on recupere tout les offres lié a la marque
            $offers = $offerRepository->findby([
                'brandId' => $user
            ]);

            //on recupere toutes les applications en lien avec la marque
            foreach($offers as $offer) {
                $applications = $offer->getApplication();
                foreach($applications as $application) {
                    //recuperer l'influenceur qui a postuler a l'offre
                    $influencer = $influencerRepository->findOneby([
                        'id' => $application->getInfluencerId()->toArray()[0]
                    ]);
                    if (strcmp($application->getStatus(), "validated") == 0) {
                        array_push($partnerships, $offer);
                        array_push($collaborators, $influencer);
                    }
                }
            }
        }
        else {
            $offers = $offerRepository->findby([ ]);
            //on recupere toutes les applications en lien avec la marque
            foreach($offers as $offer) {
                $applications = $offer->getApplication();
                foreach($applications as $application) {
                    $brand = $brandRepository->findOneby([
                        'id' => $offer->getBrandId()
                    ]);

                    if($application->getInfluencerId()->toArray()[0]->getId() == $influencerId->getId()){
                        if (strcmp($application->getStatus(), "validated") == 0) {
                            array_push($partnerships, $offer);
                            array_push($collaborators, $brand);
                        }
                    }
                }
            }
            // recuperer toutes les applications qui ont comme id notre id d'influenceur et qui ont été validé
            // renvoyer partnerships et brands
        }
        return $this->render('partnership/index.html.twig', [
            'partnerships' => $partnerships,
            'collaborators' => $collaborators
        ]);
    }
}
