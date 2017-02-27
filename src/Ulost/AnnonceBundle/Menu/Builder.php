<?php

namespace Ulost\AnnonceBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function ObjectMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');


        $em = $this->container->get('doctrine')->getManager();

        $listCategory = $em->getRepository('UlostObjectBundle:Category')->findAll();

        foreach ($listCategory as $category) {
            $menu->addChild($category->getName(), array(
                'attributes' => array('button' => 'oui')
            ));
            $menu[$category->getName()]->setChildrenAttributes(array('class' => 'panel'));
            $menu[$category->getName()]->addChild('panel', array(
                'attributes' => array('class' => 'panel')
            ));
            $menu[$category->getName()]['panel']->setLabel('');


            $menu[$category->getName()]['panel']->addChild('scroll', array(
                'attributes' => array('class' => 'bloc-scroll')
            ));
            $menu[$category->getName()]['panel']['scroll']->setLabel('');


            $menu[$category->getName()]['panel']['scroll']->addChild('listObjet', array(
                'attributes' => array('class' => 'bloc-liste-objet')
            ));
            $menu[$category->getName()]['panel']['scroll']['listObjet']->setLabel('');

            $request = $this->container->get('request_stack')->getCurrentRequest();
            $session = $request->getSession();
            $status = $session->get('status');

            $listObject = $em->getRepository('UlostObjectBundle:Object')->findByCategory($category);
            foreach ($listObject as $object) {

                $menu[$category->getName()]['panel']['scroll']['listObjet']->addChild($object->getId(), array(
                        'attributes' => array(
                            'class' => 'bloc-objet')
                    )
                );
                $menu[$category->getName()]['panel']['scroll']['listObjet'][$object->getId()]->setLabel('');
                $menu[$category->getName()]['panel']['scroll']['listObjet'][$object->getId()]->addChild('image', array(
                    'route' => 'ulost_annonce_newAnnonce',
                    'routeParameters' => array('status' => $status, 'object_id' => $object->getId()),
                    'attributes' => array('image' => 'oui', 'object_id' => $object->getId()),
                    'label' => null
                ));
                $menu[$category->getName()]['panel']['scroll']['listObjet'][$object->getId()]['image']->setLabel('');
                $menu[$category->getName()]['panel']['scroll']['listObjet'][$object->getId()]['image']->addChild($object->getTypeObjet(), array(
                        'route' => 'ulost_annonce_newAnnonce',
                        'routeParameters' => array('status' => $status, 'object_id' => $object->getId()),
                        'attributes' => array('class' => 'button')
                    )

                );

                $menu[$category->getName()]['panel']['scroll']['listObjet'][$object->getId()]['image'][$object->getTypeObjet()]->setLinkAttributes(array('class' => "button"));

            }
        }
        return $menu;
    }
}

