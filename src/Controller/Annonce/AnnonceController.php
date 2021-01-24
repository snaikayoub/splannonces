<?php

namespace App\Controller\Annonce;

use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Entity\Ville;
use App\Entity\FormSearch\AnnonceSearch;
use App\Entity\FormSearch\AnnonceSearchType;
use App\Repository\AnnonceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategorieRepository;



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
     * @Route("/annonces", name="annonces")
     * @Route("/annonces/{user}", name="mes.annonces")
     */
    public function annonces(AnnonceRepository $repo, PaginatorInterface $paginator, Request $request, $user = null): Response
    {

        $searcher = new AnnonceSearch;
        $form = $this->createForm(AnnonceSearchType::class, $searcher);
        $form->handleRequest($request);

        //dd($search);

        $annonces = $paginator->paginate(
            $repo->findfiltredNotExpiredAnnonces($searcher, $user),
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );
        return $this->render('annonce/index.html.twig', [
            'title' => 'Liste des Annonces',
            'annonces' => $annonces,
            'form' => $form->createView()
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
     * @Route("/annonce/{id}/delete", name="annonces.delete")
     */
    public function delete(AnnonceRepository $repo, $id, EntityManagerInterface $manager, Security $security): Response
    {
        $user = null;
        if ($security->getUser()) {
            $user = $security->getUser()->getUsername();
        }
        $annonce = $repo->find($id);
        if ($annonce->getContact() != $user) {
            return $this->redirectToRoute('annonces.show', [
                'id' => $id
            ]);
        }
        $manager->remove($annonce);
        $manager->flush();
        return $this->redirectToRoute('annonces');
    }

    /**
     * @Route("/annonce/new", name="annonces.create")
     *  @Route("/annonce/{id}/edit", name="annonce.edit")
     */
    public function form(Annonce $annonce = null, Request $request, EntityManagerInterface $manager, Security $security): Response
    {
        $user = null;
        if ($security->getUser()) {
            $user = $security->getUser()->getUsername();
        }

        if ($annonce && $annonce->getContact() != $user) {
            return $this->redirectToRoute('annonces.show', [
                'id' => $annonce->getId()
            ]);
        }

        if (!$annonce) {
            $annonce = new Annonce();
        }

        $form = $this->createFormBuilder($annonce)
            ->add('title')
            ->add('description')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Type' => '',
                    'Offre' => 'Offre',
                    'Demande' => 'Demande',
                ],
                'required' => 'required',
                'choice_attr' => ['Type' => ['disabled' => '']]
            ])

            ->add('imageFile1', FileType::class, ['required' => false, 'label' => 'Image Principale'])
            ->add('imageFile2', FileType::class, ['required' => false, 'label' => '2ème Image'])
            ->add('imageFile3', FileType::class, ['required' => false, 'label' => '3ème Image'])
            ->add('imageFile4', FileType::class, ['required' => false, 'label' => '4ème Image'])

            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'ville',
                'choice_value' => 'ville',
                'placeholder' => 'Choisissez une ville',
                'label' => 'Ville',
            ])
            ->add('category', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'choice_value' => 'name',
                'placeholder' => 'Choisissez une categorie',
                'label' => 'Categorie',
            ])
            ->add('price')
            ->add('contact')
            ->getForm();
        //dd($user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if (!$annonce->getId()) {
                $annonce->setCreatedAt(new \DateTime());
                if ($security->getUser()) {
                    $annonce->setContact($user);
                    $annonce->setExpired(false);
                    $manager->persist($annonce);
                    $manager->flush();

                    return $this->redirectToRoute('annonces.show', [
                        'id' => $annonce->getId()
                    ]);
                }
                $annonce->setExpired(false);
                $annonce->setContact('Anony' . $annonce->getContact());
                $manager->persist($annonce);
                $manager->flush();


                return $this->redirectToRoute('annonces.show', [
                    'id' => $annonce->getId()
                ]);
            }
            $annonce->setUpdatedAt(new \DateTime());
            $manager->persist($annonce);
            $manager->flush();

            return $this->redirectToRoute('annonces.show', [
                'id' => $annonce->getId()
            ]);
        }
        $formView = $form->createView();

        return $this->render('annonce/form.html.twig', [
            'form' => $formView,
            'annonce' => $annonce,
            'title' => 'Nouvelle Annonce'
        ]);
    }
}
