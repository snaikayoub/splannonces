<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Ville;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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

        $user = new User;
        //dd($user->getUsername(), $security->getUser());
        if ($security->getUser()) {
            return $this->redirectToRoute('logout');
        }

        $form = $this->createFormBuilder($user)
            ->add('nom')
            ->add('prenom')
            ->add('telephone', null, [
                'attr' => ['maxlength' => 10]
            ])
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
            ->add('confirm_username')
            ->add('password', PasswordType::class)
            ->add('confirm_password', PasswordType::class)
            ->add('imgprofil', FileType::class, ['required' => false, 'label' => 'Votre Photo de Profil'])
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

    /**
     * @Route("/setting/{id}",name="account.setting")
     */
    public function setting(User $user, Request $request, EntityManagerInterface $manager, Security $security, UserPasswordEncoderInterface $encoder)
    {


        if ($user->getUsername() != $security->getUser()->getUsername()) {
            return $this->redirectToRoute('logout');
        }

        $form = $this->createFormBuilder($user)
            ->add('nom')
            ->add('prenom')
            ->add('telephone', null, [
                'attr' => ['maxlength' => 10]
            ])
            ->add('ville', EntityType::class, [
                'class' => Ville::class,
                'choice_label' => 'ville',
                'choice_value' => 'ville',
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
            ->add('imgprofil', FileType::class, ['required' => false, 'label' => 'Changer votre Photo de Profil'])
            ->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            $user->setUpdatedAt(new \DateTime());
            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('account.setting', [
                'id' => $user->getId(),
                'user' => $user,
                'title' => 'Profil' . ' ' . $user->getUsername()

            ]);
        }
        $formView = $form->createView();
        return $this->render('security/setting.html.twig', [
            'form' => $formView,
            'user' => $user,
            'title' => 'Profil' . ' ' . $user->getUsername()

        ]);
    }
}
