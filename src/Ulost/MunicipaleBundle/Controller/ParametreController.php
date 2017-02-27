<?php

namespace Ulost\MunicipaleBundle\Controller;

use Ulost\CoreBundle\Entity\Annonce;
use Ulost\UserBundle\Entity\User;
use Ulost\MunicipaleBundle\Entity\Municipale;
use Ulost\MunicipaleBundle\Entity\PartenaireMunicipale;
use Ulost\MunicipaleBundle\Form\PartenaireMunicipaleType;
use Ulost\MunicipaleBundle\Entity\Employe;
use Ulost\MunicipaleBundle\Form\EmployeType;
use Ulost\MunicipaleBundle\Services\SessionMunicipale;
use Ulost\CoreBundle\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class ParametreController extends Controller
{
	public function indexAction(Request $request)
	{
		$session = $request->getSession();
		$user = $this->getUser();
		$auth= $this->isGranted('ROLE_VILLE');
		
		if (!$session ->get('municipales') || !$user || !$auth)
			{return $this->redirectToRoute('ulost_municipale_homepage');}
	
	$em=$this->getDoctrine()->getManager();
	$employes = $em ->getRepository('UlostMunicipaleBundle:Employe') ->findByUser($user);
	$partenaires = $em ->getRepository('UlostMunicipaleBundle:PartenaireMunicipale') ->findByUser($user);

	return $this->render('UlostMunicipaleBundle:Parametre:index.html.twig', 
					 array('partenaires'=>$partenaires, 'employes'=>$employes ));

	}
	
	public function employeAction(Request $request)
	{
	$session = $request->getSession();
		$user = $this->getUser();
		$auth= $this->isGranted('ROLE_VILLE');
		
		if (!$session ->get('municipales') || !$user || !$auth)
			{return $this->redirectToRoute('ulost_municipale_homepage');}
			
	$employe = new Employe();
    $form = $this->createForm(EmployeType::class, $employe);
     
	if ($request->isMethod('POST')) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
			$task = $form->getData();
			$task->setUser($user);
	//empecher l'ajout d'un employé qui existe déja
			$em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();
			return $this->redirectToRoute('ulost_municipale_parametres');
			
			}
	}
 	
	 return $this->render('UlostMunicipaleBundle:Parametre:employe.html.twig', 
					 array('form' => $form->createView()));
	}
	
	public function PartenaireAction(Request $request)
	{
	$session = $request->getSession();
		$user = $this->getUser();
		$auth= $this->isGranted('ROLE_VILLE');
		
		if (!$session ->get('municipales') || !$user || !$auth)
			{return $this->redirectToRoute('ulost_municipale_homepage');}
			
	$partenairemunicipale = new PartenaireMunicipale();
    $form = $this->createForm(PartenaireMunicipaleType::class, $partenairemunicipale);
     
	if ($request->isMethod('POST')) {
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
			$task = $form->getData();
			$task->setUser($user);
	//empecher l'ajout d'un employé qui existe déja
			$em = $this->getDoctrine()->getManager();
			$em->persist($task);
			$em->flush();
			
			return $this->redirectToRoute('ulost_municipale_parametres');
			
			}
	}
 	
	 return $this->render('UlostMunicipaleBundle:Parametre:partenaire.html.twig', 
					 array('form' => $form->createView()));
	}
}