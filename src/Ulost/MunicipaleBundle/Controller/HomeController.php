<?php

namespace Ulost\MunicipaleBundle\Controller;

use Ulost\CoreBundle\Entity\Annonce;
use Ulost\MunicipaleBundle\Entity\Service;
use Ulost\UserBundle\Entity\User;
use Ulost\MunicipaleBundle\Entity\Municipale;
use Ulost\MunicipaleBundle\Services\SessionMunicipale;
use Ulost\CoreBundle\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class HomeController extends Controller
{

	 public function indexAction(Request $request,$id)
    {
		$session = $request->getSession();
		$user = $this->getUser();
		$auth= $this->isGranted('ROLE_VILLE');
		$em =  $this->getDoctrine() ->getManager();
		if (!$session ->get('listEmplois') || !$user ){
			if (!$user)
				{
					$session->getFlashBag()->add('notice','Vous n\'avez pas de compte valide' );

					return $this->redirectToRoute('ulost_home');
				}
			if (!$auth)
				{
					$session->getFlashBag()->add('notice','Vous n\'avez pas accès à cette partie du site' );
					return $this->redirectToRoute('ulost_home');
				}


			$service=$em->getRepository('UlostMunicipaleBundle:Service')->find($id);

			if (!$service)
			{
				$session->getFlashBag()->add('notice','Ce service n\'existe pas');
				return $this->redirectToRoute('ulost_home');
			}


			$emploi= $em->getRepository('UlostMunicipaleBundle:Emploi')->findOneBy(array('user'=>$user, 'service'=>$service));

			if (!$emploi)
			{
				$session->getFlashBag()->add('notice','Vous ne travaillez pour aucun service');
				return $this->redirectToRoute('ulost_home');
			}


			$session ->set('service', $service);
			$session ->set('emploi', $emploi);
		
			}
		else
		{
			$emploi=$session->get('emploi');

		}
		
		return $this->render('UlostMunicipaleBundle:Home:index.html.twig', array(
			'emploi'=>$emploi,'service'=>$service
		));
	}


	
	public function statistiquesAction(Request $request)
	{
		$session=$request->getSession();
		$service=$session->get('service');

		$emploi=$session->get('emploi');
		return $this->render('UlostMunicipaleBundle:Home:statistiques.html.twig', array(
			'service'=>$service, 'emploi'=>$emploi
		));
	}


}
