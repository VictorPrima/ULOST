<?php
namespace Ulost\UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;

class AuthentificationController extends Controller
{
    public function loginAction(){
        return $this->render('UlostUserBundle:Authentification:login.html.twig') ;
    }

    public function registerAction(){
        return $this->render('UlostUserBundle:Authentification:register.html.twig') ;
    }

    public function logoutAction(Request $request){
        $session = $request->getSession();
        $session->clear();
        return $this->redirectToRoute("fos_user_security_logout");
    }

    public function resetPasswordAction(){
        return $this->render('UlostUserBundle:Authentification:resetPassword.html.twig');
    }

    public function checkMailAction(){
        return $this->render('UlostUserBundle:Authentification:checkMail.html.twig');
    }
}