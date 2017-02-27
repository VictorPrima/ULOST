<?php
// src/Ulost/UserBundle/Controller/HomeController.php;

namespace Ulost\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;

class AnnonceController extends Controller
{
    public function indexAction(Request $request)
    {

        return $this->render('UlostAnnonceBundle:Default:index.html.twig') ;
    }





}