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

class ClientType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('mail',   	EmailType::class, 		array( 'attr' => array(
														'pattern' => '{2,}', 
														'placeholder' => 'mail',
														)))
		->add('name', TextType::class, 		array( 'attr' => array(
														'placeholder' => 'nom',
														)))
		->add('telephone', TextType::class, 		array( 'attr' => array(
														'placeholder' => 'telephone',
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
            'data_class' => 'Ulost\MunicipaleBundle\Entity\Client'
        ));
    }
}
