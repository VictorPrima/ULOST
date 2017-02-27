<?php
// src/Ulost/UserBundle/Controller/HomeController.php;

namespace Ulost\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Ulost\MunicipaleBundle\Entity\Service;
use Ulost\UserBundle\Entity\User;

class HomeController extends Controller
{
    public function indexAction()
    {
        return $this->render('UlostUserBundle:Advert:index.html.twig');
    }

    public function menuAction()
    {
        return $this->render('UlostCoreBundle:Home:menu.html.twig');
    }

    public function nbUserByServiceAction(Request $request, Service $service)
    {
        $nbUsers = $this->getDoctrine()->getRepository('UlostUserBundle:User')->countAllUsersByService($service);
        return $this->render('UlostMunicipaleBundle:Home:showNbUsers.html.twig', array(
            'nbAnnonces' => $nbUsers
        ));
    }


    public function indexEmploiAction(Request $request)
    {
        $user = $this->getUser();
        return $this->get('EmploiAction')->indexEmploiByUserAction($request, $user->getId());
    }

    public function getNombreEmploisAction(User $user)
    {

        $em = $this->getDoctrine()->getManager();

        $NbEmplois = $em
            ->getRepository('UlostMunicipaleBundle:Emploi')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();

        return $NbEmplois;


    }

    public function redirectToServiceAfterLoginAction(Request $request)
    {

        return $this->render("UlostUserBundle:Authentification:retourAccueil.html.twig");
    }

}