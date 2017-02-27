<?php

namespace Ulost\MunicipaleBundle\Controller;

use Ulost\CoreBundle\Entity\Annonce;
use Ulost\UserBundle\Entity\User;
use Ulost\MunicipaleBundle\Entity\Municipale;
use Ulost\MunicipaleBundle\Services\SessionMunicipale;
use Ulost\CoreBundle\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class MatchController extends Controller
{

    public function indexAction(Request $request, $id)
    {
        $session = $request->getSession();
        $user = $this->getUser();
        $auth = $this->isGranted('ROLE_VILLE');
        $em = $this->getDoctrine()->getManager();
        if (!$session->get('municipales') || !$user || !$auth) {
            return $this->redirectToRoute('ulost_municipale_homepage');
        }

        $annonce = $em->getRepository('UlostCoreBundle:Annonce')
            ->findOneById($id);
        if (!$annonce) {
            throw $this->createNotFoundException('Pas d\'annonce à ce numéro');
        }

        if ($annonce->getUser() != $user) {
            $session->getFlashBag()->add('notice', 'Cette annonce n\'est pas sous votre juridiction');
            return $this->redirectToRoute('ulost_municipale_annonces_search');
        }

        return $this->render('UlostMunicipaleBundle:Match:annonce.html.twig',
            array('annonce' => $annonce));


    }

    public function clientAction(Request $request, $id)
    {
        $session = $request->getSession();
        $user = $this->getUser();
        $auth = $this->isGranted('ROLE_VILLE');
        $em = $this->getDoctrine()->getManager();
        if (!$session->get('municipales') || !$user || !$auth) {
            return $this->redirectToRoute('ulost_municipale_homepage');
        }

        $annonce = $em->getRepository('UlostCoreBundle:Annonce')
            ->findOneById($id);
        if (!$annonce) {
            throw $this->createNotFoundException('Pas d\'annonce à ce numéro');
        }

        if ($annonce->getUser() != $user) {
            $session->getFlashBag()->add('notice', 'Cette annonce n\'est pas sous votre juridiction');
            return $this->redirectToRoute('ulost_municipale_annonces_search');
        }

        return new Response('ok');

    }


    public function indexParametersAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $service = $em->getRepository('UlostMunicipaleBundle:Service')->find($id);

        return $this->render('UlostMunicipaleBundle:Match:indexParametresRecherche.html.twig', array(
            'service' => $service));
    }
}
