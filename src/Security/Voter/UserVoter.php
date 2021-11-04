<?php

namespace App\Security\Voter;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;

class UserVoter extends Voter
{
    private $security;

    public function __construct(Security  $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, ['USER_EDIT', 'USER_DELETE'])
            && $subject instanceof \App\Entity\User;
    }

    protected function voteOnAttribute(string $attribute, $utilisateur, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        if($this->security->isGranted('ROLE_ADMIN'))
        {
            return true;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($utilisateur) {
            case 'ASSOCIATION_EDIT':
                return $this->canEdit($utilisateur, $user);
                break;
            case 'ASSOCIATION_DELETE':
                return $this->canDelete($utilisateur, $user);
                break;
        }

        return false;
    }
    private function canEdit(User $utilisateur, Users $user){
        return $user === $utilisateur->getUser();

    }

    private  function canDelete(User $utilisateur, Users $user){
        return $user === $utilisateur->getUser();
    }
}
