<?php

namespace App\Controller;

use App\Entity\Transaction;
use App\Entity\Offer;

use App\Form\PaiementBrandType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
    public function success(Request $request)
    {
        //$price = 1000;
        $price = $request->get('price');
        //$idInfluencer = $request->get('idInfluencer');
       // $idOffer = $request->get('idOffer');
        $idBrand = $this->getUser();
        //$go = $this->getDoctrine()->getRepository(Offer::class)->find($id);

        $transaction = New Transaction();
        $transaction->setPrice(" . $price . ");

       // $transaction->setOfferId("43");
        dump($idBrand);

        $transaction->setBrandId($idBrand);
        $transaction->setInfluencerId($idInfluencer);

        $em = $this->getDoctrine()->getManager();
        $em->persist($transaction);
        $em->flush();

        dump($price);
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
    public function checkout(Request $request) : Response
    {

        $params = json_decode($request->getContent());

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


        return new JsonResponse(['id' => $session->id]) ;
    }

}