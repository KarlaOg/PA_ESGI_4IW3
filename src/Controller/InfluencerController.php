<?php

namespace App\Controller;

use App\Entity\Influencer;
use App\Repository\InfluencerRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InfluencerController extends AbstractController
{
    /**
     * @Route("/influenceur", name="influencer")
     */
    public function index(): Response
    {
        return $this->render('influencer/index.html.twig', [
            'controller_name' => 'InfluencerController',
        ]);
    }

    /**
     * @Route("/influenceurs", name="all_influencers")
     * @Security("is_granted('ROLE_MARQUE') or is_granted('ROLE_ADMIN')")

     */
    public function influencers_list(InfluencerRepository $influencerRepository): Response
    {
        return $this->render('influencer/list.html.twig', [
            'influencers' => $influencerRepository->findAll()
        ]);
    }

    /**
     * @Route("influenceur/{username}", name="influencer_show", methods={"GET"})
     * @Security("is_granted('ROLE_MARQUE') or is_granted('ROLE_ADMIN')")
     */
    public function show(Influencer $influencer): Response
    {
        return $this->render('influencer/show.html.twig', [
            'influencer' => $influencer
        ]);
    }
}
