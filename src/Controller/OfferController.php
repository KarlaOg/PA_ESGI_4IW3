<?php

namespace App\Controller;

use App\Entity\Application;
use App\Entity\Influencer;
use App\Entity\Offer;
use App\Entity\Comments;
use DateTime;

use App\Entity\User;
use App\Form\OfferType;
use App\Form\ApplicationType;
use App\Form\CommentsType;

use App\Repository\BrandRepository;
use App\Repository\InfluencerRepository;
use App\Repository\ApplicationRepository;
use App\Repository\OfferRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\NotifierInterface;
use Symfony\Component\Notifier\Recipient\Recipient;

/**
 * Class OfferController
 * @package App\Controller
 *
 * @Route("/offre", name="offer_")
 */

class OfferController extends AbstractController
{

    /**
     * @Route("/", name="index", methods={"GET"})
     */

    public function index(BrandRepository $brandRepository, InfluencerRepository $influencerRepository, ApplicationRepository $applicationRepository)
    {
        $repository = $this->getDoctrine()->getRepository(Offer::class);

        $user = $this->getUser();
        $brand = $brandRepository->findOneBy(['user' => $user]);

        $offers = $repository->findBy([], ['dateCreation' => 'DESC']);
        $influencer = $influencerRepository->findOneBy(['user' => $user]);
        $offerApplied = $applicationRepository->findApplicationAndInfluencer($influencer);

        $datenow = new \DateTime("now");

        $applications = $applicationRepository->findBy([
            'status' => 'validated'
        ]);

        $idsValidated = array();
        foreach ($applications as $application) {
            array_push($idsValidated, $application->getOffer()->getId());
        }

        $offers = $repository->findBy([], ['dateCreation' => 'DESC']);

        return $this->render('offer/index.html.twig', [
            'offers' =>  $offers,
            'brand' => $brand,
            'idsValidated' => $idsValidated,
            'offerApplied' => $offerApplied,
            'datenow' => $datenow
        ]);
    }
    /**
     * @Route("/nouveau", name="new", methods={"GET", "POST"})
     * @IsGranted("ROLE_MARQUE", statusCode=404, message="Vous n'avez pas accès à cette page!")
     */

    public function new(Request $request, BrandRepository $brandRepository, NotifierInterface $notifier)
    {
        $offer = new Offer();
        $user = $this->getUser();

        $brandId = $brandRepository->findOneBy(['user' => $user]);

        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offer->setBrandId($brandId);
            $em = $this->getDoctrine()->getManager();
            $em->persist($offer);
            $em->flush();

            $userEmail = $user->getEmail();
            $notification = (new Notification('Création d\'une offre  !', ['email']))
                ->content('Merci ' . $user->getLastname() . ' d\'avoir créer l\'offre ' .  $offer->getName() . ' N° ' . $offer->getId() .  ' .Les influenceurs peuvent désormais postuler à votre offre !');

            // The receiver of the Notification
            $recipient = new Recipient(
                $userEmail
            );

            // Send the notification to the recipient
            $notifier->send($notification, $recipient);

            $this->addFlash('success', 'Création réussie');

            return $this->redirectToRoute('offer_index', ['id' => $offer->getId()]);
        }


        return $this->render('offer/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/liste/{id}", name="show", methods={"GET", "POST"})
     */
    public function show($id, Offer $offer, BrandRepository $brandRepository, Request $request, ApplicationRepository $applicationRepository, OfferRepository $offerRepository, influencerRepository $influencerRepository)
    {
        $offerId = $offerRepository->find($id);

        // J'ai commenté cette ligne, car sinon impoossible de postuler à l'ofrre
        //$this->denyAccessUnlessGranted('CAN_SHOW', $offerId, "Vous n'avez pas acces");

        $user = $this->getUser();
        $brand = $brandRepository->findOneBy(['user' => $user]);

        $influencer = $influencerRepository->findOneBy(['user' => $user]);

        $offerApplied = $applicationRepository->findApplicationAndInfluencer($influencer);

        $apply = $applicationRepository->findAll();
        $influencer = $influencerRepository->findOneBy(['id' => $user]);
        // $application = $applicationRepository->find($influencer);


        $application = $applicationRepository->findBy([
            'offer' => $id
        ]);

        $pending = $applicationRepository->findBy([
            'offer' => $id,
            'status' => 'pending'
        ]);

        // Partie commentaires
        // On crée le commentaire "vierge"
        $comment = new Comments;

        // On génère le formulaire
        $commentForm = $this->createForm(CommentsType::class, $comment);

        $commentForm->handleRequest($request);

        // Traitement du formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setOffer($offer);

            // On récupère le contenu du champ parentid
            $parentid = $commentForm->get("parentid")->getData();

            // On va chercher le commentaire correspondant
            $em = $this->getDoctrine()->getManager();

            if ($parentid != null) {
                $parent = $em->getRepository(Comments::class)->find($parentid);
            }

            // On définit le parent
            $comment->setParent($parent ?? null);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('offer_show', ['id' => $offer->getId()]);
        }

        return $this->render('offer/show.html.twig', [
            'offer' => $offer,
            'brand' => $brand,
            'influencer' => $influencer,
            'offerApplied' => $offerApplied,
            'apply' => $apply,
            'application' => $application,
            'isPending' => $pending ? true : false,
            'commentForm' => $commentForm->createView()
        ]);
    }


    /**
     * @Route("/editer/{id}", name="edit", methods={"GET", "POST"})
     */
    public function edit($id, Offer $offer, Request $request, OfferRepository $offerRepository)
    {
        $offerId = $offerRepository->find($id);

        $this->denyAccessUnlessGranted('CAN_EDIT', $offerId, "Vous n'avez pas acces");

        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $this->addFlash('info', 'Modification réussie');

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/edit.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/postuler/{id}/", name="apply", methods={ "GET", "POST"})
     */
    public function apply(Offer $offer, Request $request,  NotifierInterface $notifier, influencerRepository $influencerRepository, applicationRepository $applicationRepository)
    {
        $form = $this->createForm(ApplicationType::class, $offer);
        $form->handleRequest($request);

        $user = $this->getUser();
        $offerId = $offer->getId();
        $influencer = $influencerRepository->findOneBy(['user' => $user]);
        $offerAppliedId = $applicationRepository->influencerApplyOfferId($influencer, $offer);

        if (!empty($offerAppliedId)) {
            return $this->redirectToRoute('offer_index');
        }
        $application = new Application();
        $offer->addApplication($application);
        $application->setOffer($offer);
        $application->addInfluencerId($influencer);
        $application->setStatus("pending");

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($application);
            $em->flush();
            $this->addFlash('success', 'Postuler à l\'offre en cours');

            //envoyer un email à la marque pour lui dire qu'un influenceur à postuler à l'offre
            $brandEmail = $offer->getBrandId()->getUser()->getEmail();
            $userEmail = $user->getEmail();

            $notification = (new Notification('Vous avez postuler à une offre !', ['email']))
                ->content('Merci d\'avoir postuler à l\'offre ' .  $offer->getName() . ' N° ' . $offerId . ' , la marque vous donnera bientot une réponse.');

            $notificationBrand = (new Notification('Vous avez une candidature à votre offre !', ['email']))
                ->content('Un influenceur viens de postuler à votre offre ' .  $offer->getName());

            // The receiver of the Notification
            $recipient = new Recipient(
                $userEmail
            );
            $recipient2 = new Recipient(
                $brandEmail
            );

            // Send the notification to the recipient
            $notifier->send($notification, $recipient);
            $notifier->send($notificationBrand, $recipient2);

            return $this->redirectToRoute('offer_index');
        }

        return $this->render('offer/apply.html.twig', [
            'offer' => $offer,
            'form' => $form->createView(),
            'influencer' => $influencer
        ]);
        //return $this->render('offer/validate.html.twig');
    }

    /**
     * @Route("/applications/{id}", name="show_applications")
     */
    public function show_applications($id, OfferRepository $offerRepository)
    {
        //recupere tt les applications de l'offre en question
        $applications = $offerRepository->findOneby([
            'id' => $id
        ])->getApplication();
        $influencers = array();

        //on recupere l'influenceur lié à l'application.
        foreach ($applications as $application) {
            $influencers = array_merge($influencers, $application->getInfluencerId()->toArray());
        }

        return $this->render('offer/application.html.twig', [
            'influencers' => $influencers,
            'applications' => $applications
        ]);
    }

    /**
     * @Route("/partenariat-valider/{id}", name="validated_partnership")
     */
    public function validated_partnership($id, Request $request, influencerRepository $influencerRepository, NotifierInterface $notifier, ApplicationRepository $applicationRepository, OfferRepository $offerRepository)
    {
        $applicationId = $request->get('application');

        $user = $this->getUser();
        $userEmail = $user->getEmail();

        $applications = $applicationRepository->findBy([
            'offer' => $id
        ]);

        foreach ($applications as $application) {
            if ($application->getId() == $applicationId) {
                $validate = $application->setStatus("validated");
                $em = $this->getDoctrine()->getManager();
                $em->persist($validate);
                $em->flush();

                //récuperer l'email de l'influenceur qui a postuler.
                $influencerEmail = $application->getInfluencerId()[0]->getUser()->getEmail();

                $notification = (new Notification('Nouveau Partenariat !', ['email']))
                    ->content('Bravo ! Vous avez un nouveau partenariat !');

                // Envoie un mail à la marque qui à validé la candidature d'un influenceur.
                $recipient = new Recipient(
                    $userEmail
                );
                // Envoie un mail à l'influenceur
                $recipient2 = new Recipient(
                    $influencerEmail
                );

                // Send the notification to the recipient
                $notifier->send($notification, $recipient);
                $notifier->send($notification, $recipient2);
            } else {
                $refused = $application->setStatus("refused");
                $em = $this->getDoctrine()->getManager();
                $em->persist($refused);
                $em->flush();
            }
        }
        $this->addFlash('success', 'Partenariat validé');
        $offerId = $offerRepository->find($id);

        return $this->redirectToRoute("my_partnership");
        //ne pas supprimer ce qu'il y a dessous.
        //return $this->render('offer/validate.html.twig');
    }

    /**
     * @Route("/partenariat-refuser/{id}", name="refuse_partnership")
     */
    public function refuse_partnership($id, Request $request, NotifierInterface $notifier, ApplicationRepository $applicationRepository, OfferRepository $offerRepository)
    {
        $applicationId = $request->get('application');

        $application = $applicationRepository->findOneby([
            'id' => $applicationId
        ]);

        $refuse = $application->setStatus("refused");
        $em = $this->getDoctrine()->getManager();
        $em->persist($refuse);
        $em->flush();

        $this->addFlash('success', 'Refuser le partenariat');
        $user = $this->getUser();
        $userEmail = $user->getEmail();

        $notificationBrand = (new Notification('Refuser le partenariat !', ['email']))
            ->content('Vous venez de refuser le partenariat ');

        // The receiver of the Notification
        $recipient = new Recipient(
            $userEmail
        );

        // Send the notification to the recipient
        $notifier->send($notificationBrand, $recipient);
        //  $notifier->send($notificationInfluencer, $recipient2);


        $offerId = $offerRepository->find($id);
        return $this->redirectToRoute("offer_show_applications", ['id' => $offerId->getId()]);
    }

    /**
     * @Route("/suppression/{id}/{token}", name="delete", methods={"GET"})
     * @IsGranted("ROLE_MARQUE", statusCode=404, message="Vous n'avez pas accès à cette page!")
     */
    public function delete($id, Offer $offer, $token, OfferRepository $offerRepository)
    {
        $offerId = $offerRepository->find($id);
        $this->denyAccessUnlessGranted('CAN_DELETE', $offerId, "Vous n'avez pas accès");


        if (!$this->isCsrfTokenValid('delete_offer' . $offer->getId(), $token)) {
            throw new \Exception('Token CSRF invalid');
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($offer);
        $offer->setBrandId(null);
        $em->flush();

        $this->addFlash('info', 'Suppression réussie');

        return $this->redirectToRoute('offer_index');
    }

    /**
     * @Route("/commenter/{id}/", name="comment", methods={ "GET", "POST"})
     */
    public function comment(Offer $offer, Request $request,  NotifierInterface $notifier, influencerRepository $influencerRepository)
    {

        $user = $this->getUser();
        $offerId = $offer->getId();
        $influencer = $influencerRepository->findOneBy(['user' => $user]);

        // Partie commentaires
        // On crée le commentaire "vierge"
        $comment = new Comments;

        // On génère le formulaire
        $commentForm = $this->createForm(CommentsType::class, $comment);

        $commentForm->handleRequest($request);

        // Traitement du formulaire
        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $comment->setCreatedAt(new DateTime());
            $comment->setOffer($offer);

            // On récupère le contenu du champ parentid
            $parentid = $commentForm->get("parentid")->getData();

            // On va chercher le commentaire correspondant
            $em = $this->getDoctrine()->getManager();

            if ($parentid != null) {
                $parent = $em->getRepository(Comments::class)->find($parentid);
            }

            // On définit le parent
            $comment->setParent($parent ?? null);

            $em->persist($comment);
            $em->flush();

            $this->addFlash('message', 'Votre commentaire a bien été envoyé');
            return $this->redirectToRoute('annonces_details', ['offer_id' => $offerId]);
        }
    }
}
