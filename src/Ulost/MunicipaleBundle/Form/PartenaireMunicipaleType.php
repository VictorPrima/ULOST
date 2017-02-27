<?php

namespace Ulost\MunicipaleBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PartenaireMunicipaleType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('nom',   	TextType::class, 		array( 'attr' => array(
														'pattern' => '{2,}', 
														'placeholder' => 'nom',
														)))
		->add('adresse', TextType::class, 		array( 'attr' => array(
														'placeholder' => 'adresse du partenaire',
														)))
		->add('phone', TextType::class, 		array( 'attr' => array(
														'placeholder' => 'telephone du partenaire',
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
            'data_class' => 'Ulost\MunicipaleBundle\Entity\PartenaireMunicipale'
        ));
    }
}
