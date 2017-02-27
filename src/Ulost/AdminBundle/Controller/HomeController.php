<?php
// src/Ulost/UserBundle/Controller/HomeController.php;

namespace Ulost\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class HomeController extends Controller
{
    public function indexAction()
    {

        return $this->render('UlostAdminBundle:Home:index.html.twig');
    }
    public function menuAction()
    {
        return $this->render('UlostCoreBundle:Home:menu.html.twig');
    }


}