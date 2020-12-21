<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
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
            ->add('fileName', FileType::class, ['required' => false])
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
            ->add('contact')
            ->add('price');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
