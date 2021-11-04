<?php

namespace App\Security\Voter;

use App\Entity\Association;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class AssociationsVoter extends Voter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports($attribute, $association)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['ASSOCIATION_EDIT','ASSOCIATION_DELETE' ])
            && $association instanceof \App\Entity\Association ;

    }

    protected function voteOnAttribute($attribute, $association, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // On vérifie si l'utilisateur est admin
        if($this->security->isGranted('ROLE_ADMIN')) return true;

        // On vérifie si l'asso a un propriétaire
        if(null === $association->getId()) return false;

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case 'ASSOCIATION_EDIT':
                // on vérifie si on peut éditer
                return $association->getId() == $user->getId();
                break;
            case 'ASSOCIATION_DELETE':
                // on vérifie si on peut supprimer
                return $association->getId() == $user->getId();
                break;
        }
        return false;
    }
    

}