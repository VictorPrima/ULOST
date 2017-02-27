<?php
namespace Ulost\MunicipaleBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\Request;
use Ulost\MunicipaleBundle\Entity\Emplacement;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function EmplacementPrincipalMenu(FactoryInterface $factory, array $options)
    {

        $menu = $factory->createItem('root');
        $service = $options['service'];
        $em = $this->container->get('doctrine')->getManager();

        $listEmplacementPrincipaux = $em
            ->getRepository('UlostMunicipaleBundle:Emplacement')
            ->findEmplacementsPrincipaux($service)->getResult();
        $i = 1;

        foreach ($listEmplacementPrincipaux as $emplacementPrincipaux) {
            $i++;
            $menu->addChild($emplacementPrincipaux->getId(), array(
                    'route' => 'ulost_view_emplacement',
                    'routeParameters' => array('id' => $emplacementPrincipaux->getId()),
                    'label' => $emplacementPrincipaux->getType() . ' ' . $emplacementPrincipaux->getName() . '   ' . $i)
            );
            if ($emplacementPrincipaux->getEnfants()) {
                $this->addMenu($menu, $emplacementPrincipaux, $i);
            }
        }
        return $menu;
    }

    public function addMenu($menu, Emplacement $emplacement, $i)
    {

        $em = $this->container->get('doctrine')->getManager();
        $listEmplacementEnfants = $em
            ->getRepository('UlostMunicipaleBundle:Emplacement')
            ->findby(array('parent' => $emplacement));
        foreach ($listEmplacementEnfants as $emplacementEnfants) {
            $i++;
            $menu->addChild($emplacementEnfants->getId(), array(
                    'route' => 'ulost_view_emplacement',
                    'routeParameters' => array('id' => $emplacementEnfants->getId()),
                    'label' => $emplacementEnfants->getType() . ' ' . $emplacementEnfants->getName() . '   ' . $i)
            );

            if ($emplacementEnfants->getEnfants()) {
                $menuEnfant = $menu[$emplacementEnfants->getId()];

                $this->addMenu($menuEnfant, $emplacementEnfants, $i);
            }

        }

        return $menu;
    }

}