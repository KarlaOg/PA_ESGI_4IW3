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
        $idInfluencer = $request->get('idInfluencer');
        $idOffer = $request->get('idOffer');
        $idBrand = $this->getUser();
        //$go = $this->getDoctrine()->getRepository(Offer::class)->find($id);

        $transaction = New Transaction();
        $transaction->setPrice(" . $price . ");

       // $transaction->setOfferId("43");

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


    // public function edit(){
    //     $getprice = $this->getUser();
    //     $price = new Transaction();
    //     $form = $this->createForm(PaiementBrandType::class, $getprice);
    //     $form->handleRequest($request);
    //     dump("coco");
    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $em = $this->getDoctrine()->getManager();
    //         $em->persist($getprice);
    //         $em->flush();
    //         dump("coco");
    //         $this->addFlash('info', 'Modification effectué');
          
    //     }
    //     dump("coco");
    //     return null;
    // }
  



    /**
     * @Route("/create-checkout-session", name="checkout") 
    */
    public function checkout(Request $request) : Response
    {

        $price = $request->get('price');

        \Stripe\Stripe::setApiKey('sk_test_51J4s40JmgFZZr5aDf6rWz1NB9FAJ25UTSXRVVpCv4T3TGEbZRyF20oacl8pB6dp6PH2gqteqyQhlnRbcxNaBZXbj00sBZCIiG1');
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => 10000,
                    'product_data' => [
                    'name' => 'Partnership',
                    ],
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