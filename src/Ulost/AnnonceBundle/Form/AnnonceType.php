<?php

namespace Ulost\AnnonceBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\AnnonceBundle\Form\ImageAnnonceType;
use Ulost\VilleBundle\Form\VilleType;
use Ulost\AnnonceBundle\Form\ReponseType;
use Ulost\AnnonceBundle\Entity\Reponse;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;


class AnnonceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('reponses', CollectionType::class, array(
                'entry_type' => ReponseType::class,
            ))
            ->add('remarque', TextType::class)
            ->add('imageAnnonce', ImageAnnonceType::class, array(
                "required" => false,
                "label"=>"Ajouter une image à l'annonce"
            ))
            ->add('save', SubmitType::class);
    }


    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ulost\AnnonceBundle\Entity\Annonce',
            'empty_data' => function (FormInterface $form) {
                return new Annonce(
                    $form->get('remarque')->getData(),
                    $form->get('responses')->getData()
                );
            }
        ));
    }


    public function getName()
    {
        return "UlostAnnonceBundle_AnnonceType";
    }
}
