<?php
// src/Ulost/UserBundle/Form/RegistrationType.php

namespace Ulost\UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, array(
            'label' => 'Nom',
            'required' => true
        ));
        $builder->add('firstname', TextType::class, array(
            'label' => 'Prénom',
            'required' => true
        ));

        $builder->add('phone', TextType::class, array(
            'label' => 'Numéro de Téléphone',
            'required' => false,
        ));
        $builder->add('country', ChoiceType::class, array(
                'choices' => array(
                    'France',
                    'USA'
                ),
                'preferred_choices' => array('fr'),
                'empty_data' => array('fr'),
                'label' => "Pays",
                'required' => false
            )
        );
        $builder->add('sexe', ChoiceType::class, array(
            'choices' => array(
                'm' => 'Homme',
                'f' => 'Femme'
            ),
            'required' => false,

            'empty_data' => null
        ));
        $builder->add('birthDate', BirthdayType::class, array(
            'years' => range(2010,1930 ),
            'label' => 'Date de Naissance',
            'required' => false
        ));
        $builder->add('codePostal', TextType::class, array(
                'label' => 'Code Postal',
                'required' => false)
        );
        $builder->add('town', TextType::class, array(
                'label' => 'Ville',
                'required' => false)
        );
        $builder->add('nationality', TextType::class, array(
                'label' => 'Nationalité',
                'required' => false)
        );
    }


    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\RegistrationFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }

    /**
     * Sets the default options for this type.
     *
     * @param OptionsResolverInterface $resolver The resolver for the options
     *
     * @deprecated since version 2.7, to be renamed in 3.0.
     *             Use the method configureOptions instead. This method will be
     *             added to the FormTypeInterface with Symfony 3.0.
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // TODO: Implement setDefaultOptions() method.
    }
}