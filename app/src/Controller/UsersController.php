<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Influencer;
use App\Entity\User;
use App\Form\BrandType;
use App\Form\EditProfileType;
use App\Form\InfluencerType;
use App\Repository\InfluencerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Offer;


class UsersController extends AbstractController
{

    /**
     * @Route("/users/data", name="users_data")
     */
    public function usersData()
    {
        $repository = $this->getDoctrine()->getRepository(Offer::class);
        $offers = $repository->findAll();


        return $this->render('users/data.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/users/profil/modifier", name="users_profil_modifier")
     */
    public function editProfile(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(EditProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           
            /** @var UploadedFile $brochureFile */
            $brochureFile = $form->get('image')->getData();
            
            // cette condition est nécessaire car le champ 'brochure' n'est pas obligatoire
            // donc le fichier PDF ne doit être traité que lorsqu'un fichier est téléchargé
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // cela est nécessaire pour inclure en toute sécurité le nom du fichier dans l'URL
                $safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Déplace le fichier vers le répertoire où les images sont stockées
                try {
                    $brochureFile->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    var_dump('essai');
                }

                // met à jour la propriété 'brochureFilename' pour stocker le nom du fichier PDF
                // au lieu de son contenu
                $product->setBrochureFilename($newFilename);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('green', 'Modification effectué');
            return $this->redirectToRoute('users_data');
        }

        return $this->render('users/editprofile.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/profil/create", name="users_profil_create")
     */
    public function create(Request $request, EntityManagerInterface $em,  FormFactoryInterface $factory, InfluencerRepository $influencerRepository)
    {
        if ($this->getUser()->getRoles() == ["ROLE_INFLUENCEUR"]) {

            $user = $this->getUser();
            $influencer = new Influencer();

            $form = $this->createForm(InfluencerType::class, $influencer);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $influencer->setUserId($user);

                $em->persist($influencer);
                $em->flush();

                return $this->redirectToRoute('users_data');
            }
            $formView = $form->createView();

            return $this->render('influencer/index.html.twig', [
                'formView' => $formView,
            ]);
        } else {
            $user = $this->getUser();
            $brand = new Brand();
            dd($brand);
            $form = $this->createForm(BrandType::class, $brand);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $brand->setUserId($user);

                $em->persist($brand);
                $em->flush();

                return $this->redirectToRoute('users_data');
            }
            $formView = $form->createView();

            return $this->render('brand/index.html.twig', [
                'formView' => $formView,
            ]);
        }
    }


    /**
     * @Route("/users/pass/modifier", name="users_pass_modifier")
     */
    public function editPass(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        if ($request->isMethod('POST')) {
            $em = $this->getDoctrine()->getManager();

            $user = $this->getUser();

            // On vérifie si les 2 mots de passe sont identiques
            if ($request->request->get('pass') == $request->request->get('pass2')) {
                $user->setPassword($passwordEncoder->encodePassword($user, $request->request->get('pass')));
                $em->flush();
                $this->addFlash('blue', 'Mot de passe mis à jour avec succès');

                //return $this->redirectToRoute('users');
            } else {
                $this->addFlash('red', 'Les deux mots de passe ne sont pas identiques');
            }
        }

        return $this->render('users/editpass.html.twig');
    }
}
