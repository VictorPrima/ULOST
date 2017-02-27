<?php

namespace Ulost\MunicipaleBundle\Controller;


use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Ulost\MunicipaleBundle\Entity\Service;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Ulost\MunicipaleBundle\Form\ServiceType;
use Ulost\VilleBundle\Entity\Ville;
use Ulost\VilleBundle\Entity\VilleServiceRelation;


class ServiceController extends Controller
{

    public function addServiceAction(Request $request)
    {

        // Création de l'entité Service
        $service = new Service();


        $form = $this->get('form.factory')->create(ServiceType::class, $service);


        if ($request->isMethod('POST')) {
            $name = $request->get('name');
            $villeId = $request->get('villeId');
            $ville = $this->getDoctrine()->getRepository('UlostVilleBundle:Ville')->find($villeId);
            $service->setVille($ville);
            $service->setName($name);

            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();
            if (
            $this->addRelationAction($request, $ville, $service)
            ) {
                $request->getSession()->getFlashBag()->add('notice',
                    'La Service ' . $service->getName() . ' a bien été ajoutée');
                return $this->render('UlostMunicipaleBundle:AdminService:viewService.html.twig', array(
                        'service' => $service
                    )
                );
            }
        }
        return $this->render('UlostMunicipaleBundle:AdminService:addService.html.twig', array(
                'form' => $form->createView(),
            )
        );
    }


    public function indexServiceAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $listService = $em
            ->getRepository('UlostMunicipaleBundle:Service')
            ->findAll();
        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $listService,
            $request->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
        if (null === $listService) {
            throw new NotFoundHttpException("Il n'y a pas de Services à afficher");
        }

        return $this->render('UlostMunicipaleBundle:AdminService:indexService.html.twig', array('pagination' => $pagination));
    }


    public function editServiceAction($id, Request $request)
    {
        if (!$id) {
            throw $this->createNotFoundException('Le Service ' . $id . 'n\'existe pas');
        }
        $service = $this->getDoctrine()->getRepository('UlostMunicipaleBundle:Service')->find($id);
        if (!$service) {
            throw $this->createNotFoundException('Le Service ' . $id . 'n\'existe pas');
        }

        $form = $this->get('form.factory')->create(ServiceType::class, $service);
        if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($service);
            $em->flush();


            $request->getSession()->getFlashBag()->add('notice', 'Le Service ' . $service->getName() . ' a été modifiée');

            return $this->render('UlostMunicipaleBundle:AdminService:viewService.html.twig', array(
                'service' => $service,
            ));
        }

        return $this->render('UlostMunicipaleBundle:AdminService:editService.html.twig', array(
                'form' => $form->createView(), 'service' => $service
            )
        );
    }


    public function viewServiceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $em
            ->getRepository('UlostMunicipaleBundle:Service')
            ->find($id);

        if (null === $service) {
            throw new NotFoundHttpException("Il n'y a pas de service à afficher");
        }

        return $this->render('UlostMunicipaleBundle:AdminService:viewService.html.twig', array(
            'service' => $service,
        ));

    }

    public function showServiceAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $em
            ->getRepository('UlostMunicipaleBundle:Service')
            ->find($id);

        if (null === $service) {
            throw new NotFoundHttpException("Il n'y a pas de service à afficher");
        }

        return $this->render('UlostMunicipaleBundle:AdminService:showService.html.twig', array(
            'service' => $service,
        ));

    }

    public function getNombreVillesAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $service = $em
            ->getRepository('UlostMunicipaleBundle:Service')
            ->find($id);
        $NbVilles = $em
            ->getRepository('UlostVilleBundle:VilleServiceRelation')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.service = :service')
            ->setParameter('service', $service)
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('UlostMunicipaleBundle:AdminService:showNbVilles.html.twig', array(
            'NbVilles' => $NbVilles
        ));

    }

    public function getNombreEmploisAction($id)
    {

        $em = $this->getDoctrine()->getManager();
        $service = $em
            ->getRepository('UlostMunicipaleBundle:Service')
            ->find($id);
        $NbEmplois = $em
            ->getRepository('UlostMunicipaleBundle:Emploi')
            ->createQueryBuilder('a')
            ->select('COUNT(a)')
            ->where('a.service = :service')
            ->setParameter('service', $service)
            ->getQuery()
            ->getSingleScalarResult();

        return $this->render('UlostMunicipaleBundle:Emploi:showNbEmplois.html.twig', array(
            'NbEmplois' => $NbEmplois
        ));

    }

    public function addRelationAction(Request $request, Ville $ville, Service $service)
    {
        $villeServiceRelation = new VilleServiceRelation();
        $villeServiceRelation->setService($service);
        $villeServiceRelation->setVille($ville);
        $villeServiceRelation->setName("Ville Principale");
        $villeServiceRelation->setPrincipale(true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($villeServiceRelation);
        $em->flush();

        $request->getSession()->getFlashBag()->add('notice',
            'La ville ' . $ville->getName() . ' a bien été ajoutée au service ' . $service->getName());
        return true;
    }


    public function removeServiceAction($id)
    {
        if (!$id) {
            throw $this->createNotFoundException('La question ' . $id . ' n\'existe pas');
        }
        $em = $this->getDoctrine()->getEntityManager();
        $service = $em->getRepository('UlostMunicipaleBundle:Service')->find($id);
        if (!$service) {
            throw $this->createNotFoundException('Le service ' . $id . 'n\'existe pas');
        }

        $em->remove($service);
        $em->flush();

        return $this->redirect($this->generateUrl('ulost_index_service'));

    }


}