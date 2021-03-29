<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InfluencerController extends AbstractController
{
    /**
     * @Route("/influencer", name="influencer")
     */
    public function index(): Response
    {
        return $this->render('influencer/index.html.twig', [
            'controller_name' => 'InfluencerController',
        ]);
    }
}
