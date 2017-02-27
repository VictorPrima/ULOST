<?php

namespace Ulost\MunicipaleBundle\Controller;

use Ulost\MunicipaleBundle\Entity\Stock;
use Ulost\MunicipaleBundle\Entity\Emploi;
use Ulost\MunicipaleBundle\Entity\Client;
use Ulost\CoreBundle\Entity\Contact;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\MunicipaleBundle\Form\StockType;
use Ulost\MunicipaleBundle\Form\ClientType;
use Ulost\MunicipaleBundle\Repository\StockRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class AdvertController extends Controller
{


	public function indexAnnoncesAction(Request $request)
	{
		$service=$request->getSession()->get('service');
		return $this->get('AnnonceAction')->showByServiceAction($request,$service);
	}

	 public function objetAction($perdu_trouve, Request $request)
    {
      $session = $request->getSession();
		$user = $this->getUser();
		$auth= $this->isGranted('ROLE_VILLE');
		
		if (!$session ->get('municipales') || !$user || !$auth)
			{return $this->redirectToRoute('ulost_municipale_homepage');}
		
		$listeobjet = $this->container->get('ulost_objet')->showObjet();	
		$session ->set('perdu_trouve', $perdu_trouve);	
		$em=$this->getDoctrine()->getManager();
		$category = $em ->getRepository('UlostCoreBundle:Category') ->findAll();
		if (!$listeobjet) 
		{throw $this->createNotFoundException( 'No object found');}
		return $this->render('UlostMunicipaleBundle:Advert:objet.html.twig', 
			array( 'listeobjet' => $listeobjet, 'category'=>$category));
    }
public function detailsAction(Request $request, $id)
		{
		$session = $request->getSession();
		$user = $this->getUser();
		$auth= $this->isGranted('ROLE_VILLE');
		
		if (!$session ->get('municipales') || !$user || !$auth)
			{return $this->redirectToRoute('ulost_municipale_homepage');}
			
		$em=$this->getDoctrine()->getManager();
		$objet = $em ->getRepository('UlostCoreBundle:Objet') ->findOneByUrl($id);
		if (!$objet) 
			{throw $this->createNotFoundException("Le produit que vous nous demandez, n'existe pas");}
		
		$objet_annonce = $objet->getNom();
		$session -> set('objet_annonce', $objet_annonce);
		$questions = $objet->getQuestions();

		return $this->render('UlostMunicipaleBundle:Advert:questions.html.twig',
				array(	'list_question' => $questions, 
						'objet_annonce'=>$objet_annonce));
		}	
public function contactAction(Request $request)
	{
		$session = $request->getSession();
		$user = $this->getUser();
		if (!$session ->get('municipales') || !$user)
			{return $this->redirectToRoute('ulost_municipale_homepage');}
		
		if ($request->isMethod('POST')) {			
			if ($request->request->get('question1')) 
					{ $question1 = $request->request->get('question1'); $session -> set('question1', $question1); }
			if ($request->request->get('question2')) 
					{ $question2 = $request->request->get('question2'); $session -> set('question2', $question2); }
			if ($request->request->get('question3')) 
					{ $question3 = $request->request->get('question3'); $session -> set('question3', $question3); }
			if ($request->request->get('question4')) 
					{ $question4 = $request->request->get('question4'); $session -> set('question4', $question4); }
			if ($request->request->get('question5')) 
					{ $question5 = $request->request->get('question5'); $session -> set('question5', $question5); }
			if ($request->request->get('question6')) 
					{ $question6 = $request->request->get('question6'); $session -> set('question6', $question6); }
			if ($request->request->get('question7')) 
					{ $question7 = $request->request->get('question7'); $session -> set('question7', $question7); }
			if ($request->request->get('question8')) 
					{ $question8 = $request->request->get('question8'); $session -> set('question8', $question8); }
			if ($request->request->get('question9')) 
					{ $question9 = $request->request->get('question9'); $session -> set('question9', $question9); }
			if ($request->request->get('question10')) 
					{ $question10 = $request->request->get('question10'); $session -> set('question10', $question10); }
			if ($request->request->get('remarques'))
				{ $remarques = $request->request->get('remarques'); $session -> set('remarques', $remarques); }	
			if ($request->request->get('distinctif'))
				{ $distinctif = $request->request->get('distinctif'); $session -> set('distinctif', $distinctif); }	
		
				$client = new Client();
				$form = $this->createForm(ClientType::class, $client);
				
				$em=$this->getDoctrine()->getManager();
				$partenaires = $em ->getRepository('UlostMunicipaleBundle:PartenaireMunicipale') ->findByUser($user);

				return $this->render('UlostMunicipaleBundle:Advert:contact.html.twig', array(
            'form' => $form->createView(), 'partenaires'=>$partenaires  ));		
		}
			return $this->redirect($this->generateUrl('ulost_municipale_homepage'));
	}	
public function firstAction(Request $request)
	{
	$session = $request->getSession();
	$user = $this->getUser();
	$auth= $this->isGranted('ROLE_VILLE');
		
	if (!$session ->get('municipales') || !$user || !$auth)
		{return $this->redirectToRoute('ulost_municipale_homepage');}
		
	if ($request->isMethod('POST')) 
	{
		if ($request->request->get('client'))
		{$client = $request->request->get('client'); 
		$session -> set('client', $client); }
		elseif ($request->request->get('partenaire'))
		{
			$partenaire=$request->request->get('partenaire');
			
			$em=$this->getDoctrine()->getManager();
			$partenaire = $em ->getRepository('UlostMunicipaleBundle:PartenaireMunicipale') 
						->findOneById($partenaire);
			if ($partenaire->getUser() != $user)
				{return $this->redirectToRoute('ulost_municipale_homepage');}
			$session->set('partenaire', $partenaire);
		}
		$perdu_trouve =$session ->get('perdu_trouve');
		if ($perdu_trouve=='perdu')
			{ return $this->redirectToRoute('ulost_depot_annonce_last');}
		elseif($perdu_trouve='trouvé')
			{return $this->redirectToRoute('ulost_depot_annonce_emplacement');}
		}
	return $this->redirectToRoute('ulost_municipale_homepage');
	}
	
	public function emplacementAction(Request $request)
	{
	$session = $request->getSession();
	$user = $this->getUser();
	$auth= $this->isGranted('ROLE_VILLE');
		
	if (!$session ->get('municipales') || !$user || !$auth)
		{return $this->redirectToRoute('ulost_municipale_homepage');}
		
	$municipales=$session->get('municipales');
	
	$em=$this->getDoctrine()->getManager();
	$employes = $em ->getRepository('UlostMunicipaleBundle:Employe') ->findByUser($user);
	
	return $this->render('UlostMunicipaleBundle:Advert:emplacement.html.twig', 
				array('employes'=>$employes));
    }
	public function lastAction(Request $request)
	{
		$session = $request->getSession();
		$user = $this->getUser();
		$auth= $this->isGranted('ROLE_VILLE');
		
		if (!$session ->get('municipales') || !$user || !$auth)
			{return $this->redirectToRoute('ulost_municipale_homepage');}
		
	if ($request->isMethod('POST')) {
		if ($request->request->get('emplacement')) 
			{ $emplacement = $request->request->get('emplacement'); $session -> set('emplacement', $emplacement); }
		if ($request->request->get('employe')) 
			{ $em=$this->getDoctrine()->getManager();
				$employe = $request->request->get('employe');
				$employe = $em ->getRepository('UlostMunicipaleBundle:Employe') ->findOneById($employe);
				$session -> set('employe', $employe); }
    }
	$municipales=$session->get('municipales');
	
	return $this->render('UlostMunicipaleBundle:Advert:last.html.twig',
						array('municipales'=>$municipales));
	}
	
	
	public function saveAction(Request $request)
	{
	$session = $request->getSession();
	$user = $this->getUser();
	$auth= $this->isGranted('ROLE_VILLE');
		
	if (!$session ->get('municipales') || !$user || !$auth)
			{return $this->redirectToRoute('ulost_municipale_homepage');}
		
	$municipale_retenu= $request->request->get('municipale');
	
	if ($session->has('perdu_trouve')) 
		{ $perdu_trouve = $session->get('perdu_trouve'); } else {$perdu_trouve='';}
	if ($session->has('objet_annonce')) 
		{ $objet_annonce = $session->get('objet_annonce'); } else {$objet_annonce='';}
	if ($session->has('question1')) 
		{ $question1 = $session->get('question1'); } else {$question1='';}
	if ($session->has('question2')) 
		{ $question2 = $session->get('question2'); } else {$question2='';}
	if ($session->has('question3')) 
		{ $question3 = $session->get('question3'); } else {$question3='';}
	if ($session->has('question4')) 
		{ $question4 = $session->get('question4'); } else {$question4='';}
	if ($session->has('question5')) 
		{ $question5 = $session->get('question5'); } else {$question5='';}
	if ($session->has('question6')) 
		{ $question6 = $session->get('question6'); } else {$question6='';}
	if ($session->has('question7')) 
		{ $question7 = $session->get('question7'); } else {$question7='';}
	if ($session->has('question8')) 
		{ $question8 = $session->get('question8'); } else {$question8='';}
	if ($session->has('question9')) 
		{ $question9 = $session->get('question9'); } else {$question9='';}
	if ($session->has('question10')) 
		{ $question10 = $session->get('question10'); } else {$question10='';}
	if ($session->has('ville')) 
		{ $ville = $session->get('ville'); } else {$ville='';}
	if ($session->has('remarques'))
		{ $remarques =$session->get('remarques'); } else {$remarques='';}
	if ($session->has('distinctif'))
		{ $distinctif =$session->get('distinctif'); } else {$distinctif='';}
	
	$em =  $this->getDoctrine() ->getManager();
	$am=$em;
	$municipale= $em->getRepository('UlostMunicipaleBundle:Municipale')->findOneByCodePostal($municipale_retenu);
	
	$annonce = new Annonce();
	$annonce->setPerduTrouve($perdu_trouve);
	$annonce->setObjet($objet_annonce);
	$annonce->setQuestion1($question1);
	$annonce->setQuestion2($question2);
	$annonce->setQuestion3($question3);
	$annonce->setQuestion4($question4);
	$annonce->setQuestion5($question5);
	$annonce->setQuestion6($question6);
	$annonce->setQuestion7($question7);
	$annonce->setQuestion8($question8);
	$annonce->setQuestion9($question9);
	$annonce->setQuestion10($question10);
	
	$annonce->setDistinctif($distinctif);
	$annonce->setUser($user);
	$annonce->setRemarques($remarques);
	$annonce->setDate(new \DateTime('now'));
	$annonce->setUpdatedAt(new \DateTime('now'));
	$annonce->setVille($municipale->getCodePostal());
	
	if ($session->has('client'))
		{
		$client= new Client();
		$client->setName($session->get('client')['name']);
		$client->setMail($session->get('client')['mail']);
		$client->setTelephone($session->get('client')['telephone']);
		$annonce->setClient($client);
		//$am->persist($client);
		dump($client);
		}
	
	if ($session->has('partenaire'))
		{$partenaire = $session->get('partenaire');
		$annonce->setPartenaire($partenaire);
		//dump($partenaire);
		}
	
	$emplacement ="";
	if ($session->has('emplacement') or $session->has('employe'))
	{	
		$stock = new Stock();
		$stock->setDepot(new \DateTime('now'));
		$stock->setAnnonce($annonce);
		$stock->setMunicipale($municipale);
		
		if ($session->has('emplacement'))
			{$stock->setEmplacement($session->get('emplacement'));}
		if ($session->has('employe'))
			{$stock->setEmploye($session->get('employe'));}
			
		$annonce->setStock($stock);
		dump($stock);
		//$am->persist($stock);		
	}
	dump($annonce);
	//$am->persist($annonce);
	//dump($em);
	
	//$am->flush();
	return new Response('ok');
	//$am->clear();

		//$session->getFlashBag()->add('notice', 'Votre annonce a été enregistrée' );
	//return $this->redirectToRoute('ulost_municipale_stockpage');
	}
}
