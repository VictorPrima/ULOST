<?php

//src/Ulost/MunicipaleBundle/Services/SessionMunicipale.php

namespace Ulost\MunicipaleBundle\Services;

use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Ulost\CoreBundle\Entity\Objet;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SessionMunicipale
{
	private $em = null;
    
    public function __construct(EntityManager $em) 
	{ 
		$this->em = $em;
    }
	
	public function configure(Request $request, $user)
	{
		
		$session=$request->getSession();
		if (!$session ->get('municipales') || !$user){
			
			$em =  $this->getDoctrine() ->getManager();
			$municipales= $em->getRepository('UlostMunicipaleBundle:Municipale')->findByUser($user);
			
			if (!$municipales)
				{$session->getFlashBag()->add('notice','Vous n\'avez pas de ville sous votre autorité');
				return $this->redirectToRoute('ulost_annonce_homepage');}
			
			$session ->set('municipales', $municipales);
			}
		else {$municipales=$session->get('municipales');}
		
		return ($municipales);
		
	}
}
