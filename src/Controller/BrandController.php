<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\BrandRepository ; 

class BrandController extends AbstractController
{
    /**
     * @Route("/brand", name="brand")
     */
    public function index(): Response
    {
        return $this->render('brand/index.html.twig', [
            'controller_name' => 'BrandController',
        ]);
    }

      /**
     * @Route("/all_brand", name="all_brand")
     */
    public function brands_list(BrandRepository $brandRepository) : Response
    {
        return $this->render('brand/list.html.twig', [
            'brands' => $brandRepository->findBy([], ['position' => 'ASC'])
        ]);
    }  
}
