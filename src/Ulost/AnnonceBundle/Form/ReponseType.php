<?php

namespace Ulost\AnnonceBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Ulost\AnnonceBundle\Entity\Reponse;
use Ulost\ObjectBundle\Repository\AlternativeRepository;

class ReponseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $reponse = $event->getData();
            $form = $event->getForm();

            $question = $reponse->getQuestion();
            if (!$reponse || null === $reponse->getId()) {
                if ($question->getTypeQuestion() == "checkbox") {
                    $form->add('champ', CheckboxType::class, array(
                        'label' => $question->getName(),
                        'required' => false
                    ));
                } elseif ($question->getTypeQuestion() == "textarea") {
                    $form->add('champ', TextType::class, array(
                        'label' => $question->getName()
                    ));
                } elseif ($question->getTypeQuestion() == "option") {
                    $listAlternatives = $question->getAlternatives();
                    $listChoix = array();
                    foreach ($listAlternatives as $alternative) {
                        $listChoix[] = $alternative->getName();
                    }

                    $form->add('champ', ChoiceType::class, array(
                            'choices' => $listChoix,
                            'label' => $question->getName(),
                            'expanded' => true,
                            'multiple' => false,
                            'choice_attr' => function($val, $key, $index) {
                                // adds a class like attending_yes, attending_no, etc
                                return ['class' => 'alternative'];
                            },
                        )

                    );
                } else {
                    $form->add('champ', TextType::class);
                }

            }
        });


    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Ulost\AnnonceBundle\Entity\Reponse'
        ));
    }


    public function getName()
    {
        // TODO: Implement getName() method.
    }
}
