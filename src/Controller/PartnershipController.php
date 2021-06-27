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

use App\Form\PaiementBrandType;
use Symfony\Component\HttpFoundation\Request;

class PartnershipController extends AbstractController
{
    /**
     * @Route("/mes-partenariats", name="my_partnership")
     */
    public function my_partnership(OfferRepository $offerRepository, ApplicationRepository $applicationRepository, influencerRepository $influencerRepository, brandRepository $brandRepository)
    {
        $user = $this->getUser();
        $brand = $brandRepository->findOneBy(['user' => $user->getId()]);

        $influencer = $influencerRepository->findOneBy(['user' => $user->getId()]);

        $partnerships = array();
        $collaborators = array();

        if ($brand) {
            $brandId = $brand->getId();
            $offers = $offerRepository->findBy([
                'brandId' => $brandId
            ]);
            //on recupere toutes les applications en lien avec la marque
            foreach ($offers as $offer) {
                $applications = $offer->getApplication();
                foreach ($applications as $application) {
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
        } else {
            $influencerId = $influencer->getId();
            $offers = $offerRepository->findby([]);
            //on recupere toutes les applications en lien avec la marque
            foreach ($offers as $offer) {
                $applications = $offer->getApplication();
                foreach ($applications as $application) {
                    $brand = $brandRepository->findOneby([
                        'id' => $offer->getBrandId()
                    ]);

                    if ($application->getInfluencerId()->toArray()[0]->getId() == $influencerId) {
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
            'collaborators' => $collaborators,
        ]);
       // return $this->render('paiement/checkout-page.html.twig');
    }
    /**
     * @Route("/detail-paiment", name="detail_paiement")
     */
    public function setPriceForPayment(Request $request){

        return $this->render('paiement/checkout-page.html.twig');
    }
}
