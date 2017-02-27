<?php
// src/AppBundle/Security/annonceVoter.php
namespace Ulost\CorrespondanceBundle\Security;

use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Ulost\AnnonceBundle\Controller;
use Ulost\CorrespondanceBundle\Entity\Correspondance;
use Ulost\UserBundle\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class CorrespondanceVoter extends Voter
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
        if (!$subject instanceof Correspondance) {
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
        $correspondance = $subject;


        switch ($attribute) {
            case self::VIEW:
                return $this->canView($correspondance, $user);
            case self::EDIT:
                return $this->canEdit($correspondance, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(Correspondance $correspondance, User $user)
    {
// if they can edit, they can view
        if ($this->canEdit($correspondance, $user)) {
            return true;
        }
        $em = $this->getDoctrine()->getManager();
        $listEmplois=$user->getEmplois();
        $lost = array_values($correspondance->getLost()->toArray())[0];
        foreach ($listEmplois as $emploi){
        $service=$emploi->getService();
            $listVille = $em
                ->getRepository('UlostVilleBundle:Ville')
                ->findVilleByService($service);
            foreach ($listVille as $ville)
            if ($lost->getVille==$ville){
                return true;
            }
        }

// the annonce object could have, for example, a method isPrivate()
// that checks a boolean $private property
//return !$annonce->isPrivate();

        return false;

    }

    private function canEdit(Correspondance $correspondance, User $user)
    {
// this assumes that the data object has a getUser() method
// to get the entity of the user who owns this data object
        $lost = array_values($correspondance->getLost()->toArray())[0];
        $found = array_values($correspondance->getFound()->toArray())[0];
        if ($lost->getUser() === $user || $found->getUser() === $user) {

            return true;
        } else {
            return false;
        }
    }


}