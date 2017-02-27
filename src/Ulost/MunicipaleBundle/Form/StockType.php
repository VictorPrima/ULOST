<?php

namespace Ulost\MunicipaleBundle\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Ulost\MunicipaleBundle\Repository\EmplacementRepository;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class StockType extends AbstractType
{
	/**
	 * @param FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
				$stock = $event->getData();
				$form = $event->getForm();
				$service=$stock->getService();

				$form->add('emplacement', EntityType::class, array(
                    'class' => 'UlostMunicipaleBundle:Emplacement',
                    'query_builder' => function (EmplacementRepository $er) use ($service) {
                        return $er
						->createQueryBuilder('e')
						->where('e.service = :service')
						->setParameter('service',$service)
						->andWhere('e.enfants IS empty')
						->orderBy('e.name');
					}
				,
					'choice_label' =>  'name',
					'group_by' => 'type'
				))
					->add('save', SubmitType::class);


			});

	}

	/**
	 * @param OptionsResolver $resolver
	 */
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => 'Ulost\MunicipaleBundle\Entity\Stock'
		));
	}
}
