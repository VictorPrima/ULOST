<?php

namespace Ulost\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use CoreBundle\Entity\Objet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObjetType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', 		TextType::class)
            ->add('url',		TextType::class)
            ->add('questions',	EntityType::class, array('class'=>'UlostCoreBundle:Questions', 'choice_label'=>'question'))
			->add('save', 		SubmitType::class, array('label' => 'Create Objet'))
            ->getForm();
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ulost\CoreBundle\Entity\Objet'
        ));
    }
}
