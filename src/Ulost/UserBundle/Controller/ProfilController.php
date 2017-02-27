<?php
// src/Ulost/UserBundle/Controller/ProfilController.php;

namespace Ulost\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Ulost\UserBundle\Entity\User;
use Ulost\UserBundle\Entity\Profil;
use Ulost\UserBundle\Form\editUserType;

class ProfilController extends Controller
{
    public function indexAction(Request $request)
    {

        $user = $this->getUser();
        return $this->render('UlostUserBundle:Profil:index.html.twig', array('user' => $user));
    }


    public function NombreAnnonceAction(Request $request)
    {

        $user = $this->getUser();
        $nombreAnnonce = $this->getDoctrine()->getManager()->getRepository('UlostAnnonceBundle:Annonce')->ScalarfindByUser($user);
        return $this->render('UlostUserBundle:Profil:nombreannonce.html.twig', array('nombreAnnonce' => $nombreAnnonce));
    }

    public function NombreAnnonceActiveAction(Request $request)
    {

        $user_id = $this->getUser()->getId();
        $nombreAnnonce = $this->getDoctrine()->getManager()->getRepository('UlostAnnonceBundle:Annonce')->ScalarfindByUserAndActive($user_id);
        return $this->render('UlostUserBundle:Profil:nombreannonce.html.twig', array('nombreAnnonce' => $nombreAnnonce));
    }

    public function editUserAction(Request $request)
    {


        $user = $this->getUser();
        if (!$user) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas');
        }

        $form = $this->get('form.factory')->create(editUserType::class, $user);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'Vos informations personnelles ont été modifiées');

            return $this->redirectToRoute('ulost_user_profil');
        }

        return $this->render('UlostUserBundle:Profil:edit.html.twig', array(
            'form' => $form->createView(), 'user' => $user));


    }


    public function getNameUser($id)
    {
        $user = $this->getDoctrine()->getManager()
            ->getRepository('UlostUserBundle:User')->find($id);
        $userName = $user->getUserName();
        return $userName;
    }

    public function indexSuppressionCompteAction()
    {
        return $this->render('UlostUserBundle:Profil:suppressionCompteButton.html.twig');
    }

    public function showSuppressionWarningAction(Request $request)
    {

        $suppressKey = random_int(10000, 90000);
        $request->getSession()->set('suppressKey', $suppressKey);
        return $this->render('UlostUserBundle:Profil:suppressionWarning.html.twig', array(
            'suppressKey' => $suppressKey
        ));

    }

    public function suppressionTotaleAction(Request $request, $suppressKey)
    {

        if ($request->getSession()->get('suppressKey') == $suppressKey) {
            $session = $request->getSession();
            $session->clear();
            $user=$this->getUser();
            $this->get('AnnonceAction')->removeAllAnnonceFromUserAction($request, $user->getId());
            $this->get('EmploiAction')->removeAllEmploiFromUserAction($request, $user->getId());


            $userManager = $this->get('fos_user.user_manager');
            $userManager->deleteUser($user);
            $request->getSession()->getFlashBag()->add('notice',
                'Le compte de l\'utilisateur a été supprimé');

            return $this->redirectToRoute('ulost_home');
        }
        else  $request->getSession()->getFlashBag()->add('notice',
            'Vous n\'aurez pas du finir ici');
        return $this->redirectToRoute('ulost_home');
    }

}