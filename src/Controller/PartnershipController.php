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
        //  $brand = $brandRepository->findOneBy(['user' => $user]);
        // $influencerId = $influencerRepository->findOneBy(['user' => $user ]);

        $brand = $brandRepository->findOneBy(['user' => $user->getId()]);
            
        $influencer = $influencerRepository->findOneBy(['user' => $user->getId()]);
        dump($influencer);

        $partnerships = array();
        $collaborators = array();

        if ($brand) {
            $brandId = $brand->getId();
            $offers = $offerRepository->findBy([
                'brandId' => $brandId
            ]);
            //on recupere toutes les applications en lien avec la marque
            foreach($offers as $offer) {
                $applications = $offer->getApplication();
                foreach($applications as $application) {
                    if (strcmp($application->getStatus(), "validated") == 0) {
                        //recuperer l'influenceur qui a postuler a l'offre
                        $influencer = $influencerRepository->findOneBy([
                            'id' => $application->getInfluencerId()->toArray()[0]
                        ]);
                        array_push($partnerships, $offer);
                        array_push($collaborators, $influencer);
                    }
                }
            }
        }
        else {
            $influencerId = $influencer->getId();
            dump($influencerId);
            $offers = $offerRepository->findby([ ]);
            //on recupere toutes les applications en lien avec la marque
            foreach($offers as $offer) {
                $applications = $offer->getApplication();
                foreach($applications as $application) {
                    $brand = $brandRepository->findOneby([
                        'id' => $offer->getBrandId()
                    ]);

                    if($application->getInfluencerId()->toArray()[0]->getId() == $influencerId){
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
