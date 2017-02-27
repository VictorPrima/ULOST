<?php

namespace Ulost\MailBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Ulost\UserBundle\Entity\User;

class MailController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction(Request $request)
    {
        $user=$this->getUser();
        $subject='Test';
        $body='Ceci est un test '.$user->getName();
        return $this->render('UlostMailBundle:Mail:index.html.twig', array(
            'user'=>$user, 'subject'=>$subject, 'body'=>$body
        ));

    }

    public function sendEmailAction(User $user, $subject, $body)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($subject)
            ->setFrom('admin@ulost.fr')
            ->setTo($user->getEmail())
            ->setBody($body)
        ;

        $this->get('mailer')->send($message);
        return true;



    }
}
