<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InfluencerRepository ; 

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

  /**
     * @Route("/all_influencers", name="all_influencers")
     */
    public function influencers_list(InfluencerRepository $influencerRepository) : Response
    {
        return $this->render('influencer/list.html.twig', [
            'influencers' => $influencerRepository->findAll()
        ]);
    }
    
        /**
     * @Route("influencer/show/{slug}", name="influencer_show", methods={"GET"})
     */
    public function show(Influencer $influencer): Response
    {
        return $this->render('influencer/show.html.twig', [
            'influencer' => $influencer
        ]);
    }
}
