<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\InfluencerRepository;
use App\Repository\BrandRepository;
use App\Repository\UserRepository;
use App\Repository\OfferRepository;
use App\Entity\Influencer;
use App\Entity\User;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index(): Response
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    /**
     * @Route("/search/keyworddata", name="search_keyworddata")
     */
    public function keyworddata(Request $request, InfluencerRepository $influencerRepository, BrandRepository $brandRepository, OfferRepository $offerRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $searchSelector = $request->request->get('searchSelector');

        $res = 0;

        if ($searchSelector == 'influencer') {
            $res = $influencerRepository->findAllWithNames();
        }
        elseif ($searchSelector == 'brand'){
            $res = $brandRepository->findAllWithNames();
        }
        elseif ($searchSelector == 'domain'){
            $res = $offerRepository->findAllWithNames();
        }

        return new JsonResponse($res); //return $this->render('users/editpass.html.twig');
    }

    /**
     * @Route("/data", name="search_data")
     */
    public function data(Request $request, InfluencerRepository $influencerRepository, BrandRepository $brandRepository, UserRepository $userRepository)
    {
        $em = $this->getDoctrine()->getManager();

        $searchSelect = $_POST['searchSelect'];
        $keywords = $_POST['keywords'];

        if ($searchSelect == 'influencer') {
            $rechercheRegEx = str_replace(" ", "|", $keywords);
            $rechercheRegEx = '%' . $rechercheRegEx . '%';
            $prenom = "%Mohand%";
            $query = $em->createQuery(
                "SELECT u.firstname FROM App\Entity\User u
                WHERE u.firstname LIKE ':prenom'"
            )
                ->setParameter('prenom', $prenom);
            $res = $query->getResult();
        }

        return $this->render('search/searchResult.html.twig', [
            'res' => $res
        ]);
    }
}
