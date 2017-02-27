<?php
// src/Ulost/UserBundle/Form/RegistrationType.php

namespace Ulost\UserBundle\Form;

use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class editRoleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('roles', CollectionType::class,array(

                'entry_type'   => ChoiceType::class,
                'entry_options'  => array(
                    'label' => false,
                    'choices' => array(
                        'ROLE_ADMIN' => 'Admin',
                        'ROLE_VILLE'=> 'Ville',
                        'ROLE_USER' => 'User'

                    )
                ),

                    'label'=> 'Quel rôle attribuer à cet utilisateur ?',

                )
            )
            ->add('save', SubmitType::class);
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