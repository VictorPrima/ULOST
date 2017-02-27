<?php
// src/AppBundle/Security/annonceVoter.php
namespace Ulost\AnnonceBundle\Security;

use Ulost\AnnonceBundle\Controller;
use Ulost\AnnonceBundle\Entity\Annonce;
use Ulost\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class AnnonceVoter extends Voter
{
// these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';
    private $decisionManager;

    public function __construct(AccessDecisionManagerInterface $decisionManager)
    {
        $this->decisionManager = $decisionManager;
    }

    protected function supports($attribute, $subject)
    {
// if the attribute isn't one we support, return false
        if (!in_array($attribute, array(self::VIEW, self::EDIT))) {
            return false;
        }

// only vote on annonce objects inside this voter
        if (!$subject instanceof Annonce) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
// the user must be logged in; if not, deny access
            return false;
        }
        // ROLE_SUPER_ADMIN can do anything! The power!
        if ($this->decisionManager->decide($token, array('ROLE_SUPER_ADMIN'))) {
            return true;
        }

// you know $subject is a annonce object, thanks to supports
        /** @var annonce $annonce */
        $annonce = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($annonce, $user);
            case self::EDIT:
                return $this->canEdit($annonce, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(annonce $annonce, User $user)
    {
// if they can edit, they can view
        if ($this->canEdit($annonce, $user)) {
            return true;
        }

// the annonce object could have, for example, a method isPrivate()
// that checks a boolean $private property
//return !$annonce->isPrivate();
        return false;

    }

    private function canEdit(annonce $annonce, User $user)
    {
// this assumes that the data object has a getUser() method
// to get the entity of the user who owns this data object

        return $user === $annonce->getUser();
    }


}