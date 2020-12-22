<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AnnonceController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->redirectToRoute('annonces');
    }

    /**
     * @Route("/annonce", name="annonces")
     */
    public function annonces(AnnonceRepository $repo): Response
    {
        $annonces = $repo->findAll();
        return $this->render('annonce/index.html.twig', [
            'title' => 'Liste des Annonces',
            'annonces' => $annonces
        ]);
    }

    /**
     * @Route("/annonce/{id}/show", name="annonces.show")
     */
    public function show(AnnonceRepository $repo, $id): Response
    {
        $annonce = $repo->find($id);

        return $this->render('annonce/show.html.twig', [
            'title' => 'show',
            'annonce' => $annonce
        ]);
    }

    /**
     * @Route("/annonce/new", name="annonces.create")
     *  @Route("/annonce/{id}/edit", name="annonce.edit")
     */
    public function form(Annonce $annonce = null, Request $request, EntityManagerInterface $manager): Response
    {
        if (!$annonce) {
            $annonce = new Annonce();
        }
        $form = $this->createFormBuilder($annonce)
            ->add('title')
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Type' => '',
                    'Offre' => 1,
                    'Demande' => 2,
                ],
                'required' => 'required',
                'choice_attr' => ['Type' => ['disabled' => '']]
            ])

            ->add('imageFile', FileType::class, ['required' => false])

            ->add('ville', ChoiceType::class, [
                'choices' => [
                    'Choisissez la ville de l\'annonce' => '',
                    'Safi' => 1,
                    'Marrakech' => 2,
                    'Essaouira' => 3,
                ],
                'required' => 'required',
                'choice_attr' => ['Choisissez la ville de l\'annonce' => ['disabled' => '']]
            ])
            ->add('categorie', ChoiceType::class, [
                'choices' => [
                    'Choisissez la Categorie' => '',
                    'Véhicule' => 1,
                    'Immobilier' => 2,
                    'Equipement' => 3,
                ],
                'required' => 'required',
                'choice_attr' => ['Choisissez la Categorie' => ['disabled' => '']]
            ])
            ->add('contact')
            ->add('price')
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$annonce->getId()) {
                $annonce->setCreatedAt(new \DateTime());
                $manager->persist($annonce);
                $manager->flush();
                return $this->redirectToRoute('annonces');
            }
            $annonce->setUpdatedAt(new \DateTime());
            $manager->persist($annonce);
            $manager->flush();
            return $this->redirectToRoute('annonces');
        }
        $formView = $form->createView();

        return $this->render('annonce/form.html.twig', [
            'form' => $formView,

            'title' => 'Nouvelle Annonce'
        ]);
    }
}
