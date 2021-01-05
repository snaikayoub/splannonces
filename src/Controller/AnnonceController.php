<?php

namespace App\Controller;

use App\Entity\Annonce;
use App\Entity\AnnonceSearch;
use App\Entity\Categorie;
use App\Form\AnnonceSearchType;
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
    public function annonces(AnnonceRepository $repo, PaginatorInterface $paginator, Request $request): Response
    {
        $search = new AnnonceSearch;
        $form = $this->createForm(AnnonceSearchType::class, $search);
        $form->handleRequest($request);

        //dd($search);

        $annonces = $paginator->paginate(
            $repo->findAllNotExpiredQueryFilter($search),
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
    public function delete(AnnonceRepository $repo, $id, EntityManagerInterface $manager): Response
    {
        $annonce = $repo->find($id);
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
        $user = $security->getUser()->getUsername();

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

            ->add('imageFile1', FileType::class, ['required' => false, 'label' => 'Image Principale'])
            ->add('imageFile2', FileType::class, ['required' => false, 'label' => '2ème Image'])
            ->add('imageFile3', FileType::class, ['required' => false, 'label' => '3ème Image'])
            ->add('imageFile4', FileType::class, ['required' => false, 'label' => '4ème Image'])

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
            ->add('category', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'choice_value' => 'name',
                'placeholder' => 'Choisissez une categorie',
                'label' => 'Categorie',
            ])
            ->add('contact')
            ->add('price')
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            if (!$annonce->getId()) {
                $annonce->setCreatedAt(new \DateTime());
                $annonce->setContact($user);
                $annonce->setExpired(false);

                $manager->persist($annonce);
                $manager->flush();


                return $this->redirectToRoute('annonces.show', [
                    'id' => $annonce->getId()
                ]);
            }
            /*if ($annonce->getImageFile1() instanceof UploadedFile) {
                $cacheManager->remove($uploaderHelper->asset($annonce, 'imageFile1'));
            }
            if ($annonce->getImageFile2() instanceof UploadedFile) {
                $cacheManager->remove($uploaderHelper->asset($annonce, 'imageFile2'));
            }
            if ($annonce->getImageFile3() instanceof UploadedFile) {
                $cacheManager->remove($uploaderHelper->asset($annonce, 'imageFile3'));
            }
            if ($annonce->getImageFile4() instanceof UploadedFile) {
                $cacheManager->remove($uploaderHelper->asset($annonce, 'imageFile4'));
            }*/
            $annonce->setUpdatedAt(new \DateTime());
            $annonce->setContact($user);

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
