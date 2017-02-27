<?php

namespace Ulost\UserBundle\Redirection;


use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;


class AfterRegisterRedirection implements AuthenticationSuccessHandlerInterface
{
    /**
     * @var \Symfony\Component\Routing\RouterInterface
     */
    private $router;
    /**
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }
    /**
     * @param Request $request
     * @param TokenInterface $token
     * @return RedirectResponse
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token)
    {
        // Get list of roles for current user

        $session = $request->getSession();
        // Tranform this list in array
        if ($session->has('annonce_id'))
        {
            $annonce_id = $session->get('annonce_id');
            $redirection = new RedirectResponse($this->router->generate('ulost_annonce_enregistrer', array('annonce_id'=>$annonce_id)));
        }

        else
            $redirection = new RedirectResponse($this->router->generate('ulost_home'));

        return $redirection;
    }
}