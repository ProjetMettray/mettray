<?php

namespace App\Security\Voter;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class UsersVoter extends Voter
{
    const USER_EDIT = 'USER_EDIT';
    const USER_DELETE = 'USER_DELETE';
    const USER_SHOW = 'USER_SHOW';
    
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $user)
    {
    
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::USER_EDIT,self::USER_DELETE,self::USER_SHOW ])
            && $user instanceof \App\Entity\User ;
          
    }

    protected function voteOnAttribute($attribute, $user, TokenInterface $token): bool
    {
       
        $currentUser = $token->getUser();
       
        if (!$currentUser instanceof UserInterface) {
            return redirect()->back();
        }

        if($this->security->isGranted('ROLE_ADMIN')){
            return true;

        }

        if(null === $user) return redirect()->back();

        switch ($attribute) {
            case self::USER_EDIT:
                return $this->canEdit($user, $currentUser);
                break;
            case self::USER_DELETE:
                return $this->canDelete($user, $currentUser);
                break;
            case self::USER_SHOW:
                return $this->canShow($user, $currentUser);
                break;            
        }
        return redirect()->back();
    }
    private function canEdit(User $user, $currentUser)
    {
        if ( $user->getId() === $currentUser->getId()){
            return true;
        }
        else {
            return redirect()->back();
        }
    }
    private function canDelete(User $user, $currentUser)
    {
        if ( $user->getId() === $currentUser->getId()){
            return true;
        }
        else {
            return redirect()->back();
        }
    }
    private function canShow(User $user, $currentUser)
    {
        if ( $user->getId() === $currentUser->getId()){
            return true;
        }
        else {
            return $currentUser->redirectToRoute('user_index');
        }
    }
}
