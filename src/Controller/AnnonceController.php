<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonce;
use App\Repository\AnnonceRepository;

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
     * @Route("/annonce/show/{id}", name="annonces.show")
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
     * @Route("/annonce/create", name="annonces.create")
     */
    public function create(): Response
    {

        return $this->render('annonce/show.html.twig', [
            'title' => 'show'
        ]);
    }
}
