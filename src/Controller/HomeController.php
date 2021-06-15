<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $userLogged = $this->getUser();

        if ($userLogged) {
            return $this->redirectToRoute('users_data');
        }

        $repository = $this->getDoctrine()->getRepository(Offer::class);
        $offers = $repository->findBy(array(), array('id' => 'desc'), 4, 0);

        return $this->render('home/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/categoryOffer", name="categoryOffer")
     */
    public function category_offer(): Response
    {
        return $this->render('home/offer.html.twig', [
            'controller_name' => 'BrandController',
        ]);
    }

    /**
     * @Route("/categoryBrand", name="categoryBrand")
     */
    public function category_brand(): Response
    {
        return $this->render('home/brand.html.twig', [
            'controller_name' => 'BrandController',
        ]);
    }
}
