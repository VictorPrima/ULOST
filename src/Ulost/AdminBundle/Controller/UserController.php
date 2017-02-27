<?php
// src/Ulost/UserBundle/Controller/HomeController.php;

namespace Ulost\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use Ulost\UserBundle\Form\editRoleType;

class UserController extends Controller
{
    public function indexUsersAction()
    {

        $listUsers=$this->getDoctrine()->getRepository('UlostUserBundle:User')->findAll();
        return $this->render('UlostAdminBundle:User:index.html.twig', array('listUsers'=>$listUsers));
    }


    public function editUserAction(Request $request,$id)
    {

        if (!$id) {
            throw $this->createNotFoundException('L\'utilisateur ' . $id . 'n\'existe pas');
        }
        $user=$this->getDoctrine()->getRepository('UlostUserBundle:User')->find($id);
        if (!$user) {
            throw $this->createNotFoundException('L\'utilisateur ' . $id . 'n\'existe pas');
        }

        $form = $this->get('form.factory')->create(editRoleType::class, $user);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'Le rôle de l\'utilisateur ' . $user->getId() . ' a été modifié');
            return $this->redirectToRoute('ulost_index_users');
        }

        return $this->render('UlostAdminBundle:User:edit.html.twig', array(
                'form' => $form->createView(), 'user' => $user
            )
        );


    }
}