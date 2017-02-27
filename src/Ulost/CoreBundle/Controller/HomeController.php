<?php
// src/Ulost/CoreBundle/Controller/HomeController.php

namespace Ulost\CoreBundle\Controller;

use Ulost\CoreBundle\Entity\Contact;
use Ulost\CoreBundle\Entity\Partenaire;
use Ulost\CoreBundle\Form\PartenaireType;
use Ulost\CoreBundle\Form\ContactType;
use Ulost\CoreBundle\Entity\faq;
use Ulost\CoreBundle\Form\faqType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove("suppressKey");
        return $this->render('UlostCoreBundle:Home:index.html.twig');
    }

    public function indexdefaultAction(Request $request){
        return $this->redirectToRoute('ulost_home');
    }


    public function contactAction(Request $request)
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $task = $form->getData();
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->flush();
                return $this->redirect($this->generateUrl('ulost_annonce_homepage'));
            }
        }
        return $this->render('UlostCoreBundle:Home:contact.html.twig', array(
            'form' => $form->createView()));
    }

    public function contactpageAction(Request $request)
    {
        return $this->render('UlostCoreBundle:Home:contact_page.html.twig');
    }

    public function faqAction($nb_faq)
    {
        $listfaq = $this->getDoctrine()->getManager()
            ->getRepository('UlostCoreBundle:faq')->findAll();
        return $this->render('UlostCoreBundle:Home:faq.html.twig',
            array('listfaq' => $listfaq, 'nb_faq' => $nb_faq));
    }

    public function menuAction()
    {
        return $this->render('UlostCoreBundle:Home:menu.html.twig');
    }

    public function faqpageAction()
    {
        $nb_faq = 20;
        return $this->render('UlostCoreBundle:Home:faq_page.html.twig', array('nb_faq' => $nb_faq));
    }

    public function partenaireAction(Request $request)
    {
        $partenaire = new Partenaire();
        $form = $this->createForm(PartenaireType::class, $partenaire);

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $task = $form->getData();
                $task->setDate(date("Y-m-d H:i:s"));
                $em = $this->getDoctrine()->getManager();
                $em->persist($task);
                $em->flush();
                return $this->redirect($this->generateUrl('ulost_annonce_homepage'));
            }
        }
        return $this->render('UlostCoreBundle:Home:partenaire.html.twig', array(
            'form' => $form->createView()));
    }

    public function fonctionnementAction()
    {
        return $this->render('UlostCoreBundle:Home:fonctionnement.html.twig');
    }


    public function testAction()
    {
        return $this->render('UlostCoreBundle:Home:test.html.twig');
    }

    public function indexLanguageAction()
    {
        return $this->render('UlostCoreBundle:Home:language.html.twig');
    }

    public function showFooterAction()
    {
        return $this->render('UlostCoreBundle:Home:footer.html.twig');
    }
}