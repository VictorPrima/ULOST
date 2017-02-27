<?php

namespace Ulost\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PartenaireType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('nom',   		TextType::class, 		array( 'attr' => array(
														'pattern' => '{2,}', 
														'placeholder' => 'Vous',
														)))
		->add('entreprise', TextType::class, 		array( 'attr' => array(
														'placeholder' => 'Votre organisation'
														)))
		->add('mail', 		EmailType::class, 		array('attr' => array(
														'placeholder' => 'Votre mail')))
		->add('telephone', 	NumberType::class,		array('attr' => array(
														'placeholder' => 'Votre Téléphone')))
		->add('ville', 		TextType::class, 		array( 'attr' => array(
														'placeholder' => 'la Ville de l\'entreprise'
														)))
		->add('message', 	TextareaType::class, 	array( 'attr' => array(
														'cols' => 50,
														'rows' => 7,
														'placeholder' => 'Votre message ...'
														)))
		->add('save',   	SubmitType::class)
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ulost\CoreBundle\Entity\Partenaire'
        ));
    }
}
