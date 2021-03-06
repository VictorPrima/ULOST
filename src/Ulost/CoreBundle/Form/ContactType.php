<?php

namespace Ulost\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
		->add('name',   	TextType::class, 		array( 'attr' => array(
														'pattern' => '{2,}', 
														'placeholder' => 'ici',
														)))
		->add('mail', 		EmailType::class, 		array( 'attr' => array(
														'placeholder' => 'ici',
														)))
		->add('message', 	TextareaType::class, 	array( 'attr' => array(
														'cols' => 20,
														'rows' => 3,
														'placeholder' => 'ici',
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
            'data_class' => 'Ulost\CoreBundle\Entity\Contact'
        ));
    }
}
