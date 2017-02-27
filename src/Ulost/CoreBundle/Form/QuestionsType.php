<?php

namespace Ulost\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question')
            ->add('code_html')
            ->add('rep1')
            ->add('rep2')
            ->add('rep3')
            ->add('rep4')
            ->add('rep5')
            ->add('rep6')
            ->add('rep7')
            ->add('rep8')
            ->add('rep9')
            ->add('rep10')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ulost\CoreBundle\Entity\Questions'
        ));
    }
}
