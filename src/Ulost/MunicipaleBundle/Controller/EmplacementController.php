<?php

namespace Ulost\MunicipaleBundle\Controller;


use Ulost\MunicipaleBundle\Entity\Emplacement;
use Ulost\MunicipaleBundle\Entity\Emploi;
use Ulost\MunicipaleBundle\Entity\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ulost\MunicipaleBundle\Form\EmplacementType;
use Ulost\MunicipaleBundle\Form\EmploiType;


class EmplacementController extends Controller
{


    public function addEmplacementPrincipalAction(Request $request, $id)
    {

        $session = $request->getSession();
        $session->set('emplacement', null);
        $session->set('service', $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Service')->find($id));

        return $this->addEmplacementAction($request);
    }


    public function addEmplacementEnfantAction(Request $request, $id)
    {
        $session = $request->getSession();

        $emplacement = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Emplacement')->find($id);
        $session->set('emplacement', $emplacement);
        $session->set('service', $emplacement->getService());
        return $this->addEmplacementAction($request);
    }

    public function addEmplacementAction(Request $request)
    {
        $service = $request->getSession()->get('service');
        // Création de l'entité Service
        $emplacement = new Emplacement();


        $form = $this->get('form.factory')->create(EmplacementType::class, $emplacement);


        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            $emplacement->setService($service);
            $listEnfants=$emplacement->getEnfants();
            foreach ($listEnfants as $enfants){
                $enfants->setService($service);
            }
            $parent = $request->getSession()->get('emplacement');
            if ($parent != null) {
                $parent->addEnfant($emplacement);
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($emplacement);
            $em->flush();

            return $this->viewEmplacementAction($request, $emplacement->getId());
        }
        return $this->render('UlostMunicipaleBundle:Emplacement:addEmplacement.html.twig', array(
                'form' => $form->createView(), 'service' => $service
            )
        );
    }

    public function editEmplacementAction($id, Request $request)
    {
        if (!$id) {
            throw $this->createNotFoundException('L\'emplacement ' . $id . 'n\'existe pas');
        }
        $emplacement = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Emplacement')->find($id);
        if (!$emplacement) {
            throw $this->createNotFoundException('Le Service ' . $id . 'n\'existe pas');
        }
        $service = $emplacement->getService();
        $form = $this->get('form.factory')->create(EmplacementType::class, $emplacement, array(

            )
        );

        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {


            $listEnfants=$emplacement->getEnfants();
            foreach ($listEnfants as $enfants){
                $enfants->setService($service);
            }


            $em = $this->getDoctrine()->getManager();
            $em->persist($emplacement);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'L\'emplacement ' . $emplacement->getType() . " " . $emplacement->getName() . ' a été modifiée');

            return $this->viewEmplacementAction($request, $emplacement->getId());
        }

        return $this->render('UlostMunicipaleBundle:Emplacement:addEmplacement.html.twig', array(
                'form' => $form->createView(), 'parent' => $emplacement->getParent(), 'service' => $emplacement->getService()
            )
        );
    }

    public function indexEmplacementPrincipauxAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository('UlostMunicipaleBundle:Service')->find($id);
        $session = $request->getSession();
        $session->set('service', $service);
        $listEmplacement = $em
            ->getRepository('UlostMunicipaleBundle:Emplacement')
            ->findEmplacementsPrincipaux($service);
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listEmplacement,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        if (null === $listEmplacement) {
            throw new NotFoundHttpException("Il n'y a pas d'emploi à afficher");
        }

        return $this->render('UlostMunicipaleBundle:Emplacement:indexEmplacementPrincipaux.html.twig', array(
            'pagination' => $pagination, 'service' => $service
        ));

    }

    public function indexEnfantsAction(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $emplacement = $em->getRepository('UlostMunicipaleBundle:Emplacement')->find($id);
        $listEmplacement = $em
            ->getRepository('UlostMunicipaleBundle:Emplacement')
            ->findby(array('parent' => $emplacement));
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listEmplacement,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        if (null === $listEmplacement) {
            throw new NotFoundHttpException("Il n'y a pas d'emploi à afficher");
        }

        return $this->render('UlostMunicipaleBundle:Emplacement:indexEmplacement.html.twig', array(
            'pagination' => $pagination, 'service' => $emplacement->getService()
        ));

    }


    public function viewEmplacementAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $emplacement = $em
            ->getRepository('UlostMunicipaleBundle:Emplacement')
            ->find($id);
        $request->getSession()->set('emplacement', $emplacement);
        if (null === $emplacement) {
            throw new NotFoundHttpException("Il n'y a pas d'emplacement à afficher");
        }

        return $this->render('UlostMunicipaleBundle:Emplacement:viewEmplacement.html.twig', array(
            'emplacement' => $emplacement,
        ));

    }

    public function getNombreEnfantsAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $emplacement = $em
            ->getRepository('UlostMunicipaleBundle:Emplacement')
            ->find($id);
        $NbEnfants = $em
            ->getRepository('UlostMunicipaleBundle:Emplacement')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.parent = :parent')
            ->setParameter('parent', $emplacement)
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('UlostMunicipaleBundle:Emplacement:showNbEnfants.html.twig', array(
            'NbEnfants' => $NbEnfants
        ));

    }


    public function showMenuEmplacementAction(Request $request, Service $service)
    {

        return $this->render("UlostMunicipaleBundle:Emplacement:indexMenuEmplacement.html.twig", array(
            'service' => $service
        ));
    }
}