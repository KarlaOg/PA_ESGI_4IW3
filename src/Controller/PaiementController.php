<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class PaiementController extends AbstractController
{
    /**
     * @Route("/paiement", name="paiement")
     */
    public function index(): Response
    {
        return $this->render('paiement/index.html.twig', [
            
        ]);
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
    public function checkout()
    {
        \Stripe\Stripe::setApiKey('sk_test_51J4s40JmgFZZr5aDf6rWz1NB9FAJ25UTSXRVVpCv4T3TGEbZRyF20oacl8pB6dp6PH2gqteqyQhlnRbcxNaBZXbj00sBZCIiG1');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                'name' => 'Partnership',
                ],
                'unit_amount' => 10000,
            ],
            'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $this->generateUrl('success', [], UrlGeneratorInterface::ABSOLUTE_URL),
            'cancel_url' => $this->generateUrl('errorcheckout', [], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
        
    return new JsonResponse(['id' => $session->id]) ;

    }

}
