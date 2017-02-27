<?php

namespace Ulost\MunicipaleBundle\Controller;


use Ulost\MunicipaleBundle\Entity\Emploi;
use Ulost\MunicipaleBundle\Entity\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ulost\MunicipaleBundle\Form\EmploiType;


class EmploiController extends Controller
{

    public function addEmploiAction(Request $request, Service $service)
    {

        // Création de l'entité Service
        $emploi = new Emploi();


        $form = $this->get('form.factory')->create(EmploiType::class, $emploi);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $emploi->setService($service);
            $user = $emploi->getUser();
            if ($user->getRoles() == 'ROLE_USER') {
                $user->setRoles('ROLE_VILLE');
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($emploi);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice',
                'L\'emploi ' . $emploi->getRole() . ' a bien été ajoutée');
            return $this->render('UlostMunicipaleBundle:AdminService:viewService.html.twig', array(
                'service' => $service,
            ));
        }
        return $this->render('UlostMunicipaleBundle:Emploi:addEmploi.html.twig', array(
                'form' => $form->createView(), 'service' => $service
            )
        );
    }


    public function indexEmploiAction(Request $request, Service $service)
    {

        $em = $this->getDoctrine()->getManager();
        $listEmploi = $em
            ->getRepository('UlostMunicipaleBundle:Emploi')
            ->findAllByService($service);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listEmploi,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        if (null === $listEmploi) {
            throw new NotFoundHttpException("Il n'y a pas d'emplois à afficher");
        }

        return $this->render('UlostMunicipaleBundle:Emploi:indexEmploi.html.twig', array('pagination' => $pagination));
    }


    public function editEmploiAction($id, Request $request)
    {
        if (!$id) {
            throw $this->createNotFoundException('L\'emploi ' . $id . 'n\'existe pas');
        }
        $emploi = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Emploi')->find($id);
        if (!$emploi) {
            throw $this->createNotFoundException('L\'emploi ' . $id . 'n\'existe pas');
        }

        $form = $this->get('form.factory')->create(EmploiType::class, $emploi);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($emploi);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'L\'emploi' . $emploi->getRole() . ' a été modifiée');

        }

        return $this->render('UlostMunicipaleBundle:Emploi:editEmploi.html.twig', array(
                'form' => $form->createView(), 'emploi' => $emploi
            )
        );
    }

    public function indexEmploiByServiceAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $listEmploi = $em
            ->getRepository('UlostMunicipaleBundle:Emploi')
            ->findByService($id, array('role' => 'ASC'));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listEmploi,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        if (null === $listEmploi) {
            throw new NotFoundHttpException("Il n'y a pas d'emploi à afficher");
        }

        return $this->render('UlostMunicipaleBundle:Emploi:showEmploi.html.twig', array(
            'pagination' => $pagination
        ));

    }

    public function removeEmploiFromServiceAction(Request $request, $id)
    {
        if (!$id) {
            throw $this->createNotFoundException('L\'emploi ' . $id . ' n\'existe pas');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $emploi = $em->getRepository('UlostMunicipaleBundle:Emploi')->find($id);
        if (!$emploi) {
            throw $this->createNotFoundException('L\'emploi ' . $id . 'n\'existe pas');
        }
        $service = $emploi->getService();
        if($this->removeEmploiAction($emploi)){
            $request->getSession()->getFlashBag()->add('notice',
                'L\'emploi '.$id.' a été supprimé');

        }
        return $this->redirect($this->generateUrl('ulost_view_service', array(
            'id' => $service->getId()
        )));
    }

    public function removeEmploiAction($emploi)
    {

        $em = $this->getDoctrine()->getEntityManager();

        $em->remove($emploi);
        $em->flush();

        return true;

    }


    public function removeAllEmploiFromUserAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository('UlostUserBundle:User')->find($id);

        $listEmplois = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Emploi')->findBy(array('user' => $user));
        foreach ($listEmplois as $emploi) {

            $this->removeEmploiAction($request, $emploi);
        }
        $request->getSession()->getFlashBag()->add('notice',
            'Toutes les emplois de l\'utilisateur ' . $user->getUsername() . ' ont bien été supprimées');
        return true;
    }


    public function showEmploiByUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository('UlostUserBundle:User')->find($id);
        $listEmploi = $em
            ->getRepository('UlostMunicipaleBundle:Emploi')
            ->findByUser($user);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listEmploi,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        if (null === $listEmploi) {
            throw new NotFoundHttpException("Il n'y a pas d'emploi à afficher");
        }

        return $this->render('UlostMunicipaleBundle:Emploi:showEmploi.html.twig', array(
            'pagination' => $pagination
        ));

    }

    public function indexEmploiByUserAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em
            ->getRepository('UlostUserBundle:User')->find($id);
        return $this->render('UlostMunicipaleBundle:Emploi:indexEmploi.html.twig', array(
            'user' => $user
        ));
    }

    public function getlistEmploi()
    {
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $listEmploi = $listEmploi = $em
            ->getRepository('UlostMunicipaleBundle:Emploi')
            ->findByUser($user);
        return $listEmploi;
    }
}