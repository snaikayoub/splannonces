<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login",name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error

        ]);
    }

    /**
     * @Route("/register",name="register")
     */
    public function register(Request $request, EntityManagerInterface $manager, Security $security, UserPasswordEncoderInterface $encoder)
    {

        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('nom')
            ->add('prenom')
            ->add('telephone')
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'ville',
                'choice_value' => 'ville',
                'placeholder' => 'Choisissez une ville',
                'label' => 'Votre ville',
            ])
            ->add('organisation', ChoiceType::class, [
                'choices' => [
                    'Organisation' => '',
                    'Particulier' => 1,
                    'professionnel' => 2,
                ],
                'required' => 'required',
                'choice_attr' => ['Organisation' => ['disabled' => '']]
            ])
            ->add('username')
            ->add('password')
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user->setCreatedAt(new \DateTime());
            $user->setPassword($encoder->encodePassword($user, $user->getPassword()));
            $manager->persist($user);
            $manager->flush();


            return $this->redirectToRoute('login');
        }
        $formView = $form->createView();
        return $this->render('security/register.html.twig', [
            'form' => $formView,
            'title' => 'Nouvel Utilisateur'
        ]);
    }
}
