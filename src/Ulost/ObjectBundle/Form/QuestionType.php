<?php

namespace Ulost\ObjectBundle\Form;

use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class QuestionType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('ordre', IntegerType::class, array(
                'attr' => array('min' => 0, 'max' => 10)))
            ->add('coefficient', IntegerType::class, array(
                'attr' => array('min' => 0, 'max' => 10),
                'empty_data' => 1))
            ->add('typeQuestion', ChoiceType::class, array(
                    'choices' => array(
                        'textarea' => 'textarea',
                        "checkbox" => "checkbox",
                        'option' => 'option',
                    ),
                    'expanded' => true,
                    'multiple' => false,
                    'label' => 'Choisissez le type de question',
                    'empty_data' => 'textarea'
                )
            )
            ->add('obligatoire', CheckboxType::class, array(
                    'label' => 'Cette question doit-elle être obligatoirement remplie ?',
                    'required' => false,
                )
            )
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $question = $event->getData();
                $form = $event->getForm();
                if ($question->getTypeQuestion() == "option") {
                    $form->add('alternatives', CollectionType::class, array(
                        'label' => 'Alternatives',
                        'entry_type' => AlternativeType::class,
                        'required' => false,
                        'allow_add' => true,
                        'allow_delete' => true,
                        'by_reference' => false
                    ));
                }
            }
            )
            ->add('save', SubmitType::class);
    }

    /**
     * @param OptionsResolver $resolver
     */
    public
    function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ulost\ObjectBundle\Entity\Question'
        ));
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
    public
    function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // TODO: Implement setDefaultOptions() method.
    }

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     *
     * @deprecated Deprecated since Symfony 2.8, to be removed in Symfony 3.0.
     *             Use the fully-qualified class name of the type instead.
     */

    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     *
     * @deprecated Deprecated since Symfony 2.8, to be removed in Symfony 3.0.
     *             Use the fully-qualified class name of the type instead.
     */
    public
    function getName()
    {
        // TODO: Implement getName() method.
    }
}
