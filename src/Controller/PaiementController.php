<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Offer;

use App\Form\PaiementBrandType;

use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
use App\Repository\OfferRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PaiementController extends AbstractController
{
    /**
     * @Route("/paiement", name="paiement")
     */
    public function index(): Response
    {
        return $this->render('paiement/index.html.twig', []);
    }

    /**
     * @Route("/success", name="success")
     */
    public function success(): Response
    {
        return $this->render('paiement/success.html.twig');
    }


    /**
     * @Route("/errorcheckout", name="errorcheckout")
     */
    public function errorcheckout(): Response
    {
        return $this->render('paiement/errorcheckout.html.twig');
    }


    /**
     * @Route("/create-checkout-session", name="checkout")
     */
    public function checkout(Request $request, BrandRepository $brandRepository, InfluencerRepository $influencerRepository, OfferRepository $offerRepository)
    {

        $params = json_decode($request->getContent());

        $transactionData = $params->sessionStorage;

        $user = $this->getUser();
        $brand = $brandRepository->findBy(['user' => $user->getId()])[0];
        $influencer = $influencerRepository->findBy(['username' => $transactionData->influencerUsername])[0];
        $offer = $offerRepository->findBy(['id' => $transactionData->offerId])[0];
        
        $transaction = new Transaction();
        $transaction->setBrandId($brand);
        $transaction->setOfferId($offer);
        $transaction->setInfluencerId($influencer);
        $transaction->setPrice($transactionData->price);

        $em = $this->getDoctrine()->getManager();
        $em->persist($transaction);
        $em->flush();

        \Stripe\Stripe::setApiKey('sk_test_51J4s40JmgFZZr5aDf6rWz1NB9FAJ25UTSXRVVpCv4T3TGEbZRyF20oacl8pB6dp6PH2gqteqyQhlnRbcxNaBZXbj00sBZCIiG1');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'submit_type' => 'donate',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'product_data' => [
                        'name' => 'Partnership',
                    ],
                    'unit_amount' => $params->checkoutCustomSum,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('errorcheckout', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        //$this->transaction($request);

        return new JsonResponse(['id' => $session->id]);
    }
}
