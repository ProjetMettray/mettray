<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class EventVoter extends Voter
{
    const EVENT_EDIT = 'event_edit' ;
        const EVENT_DELETE = 'event_delete';
        
    protected function supports(string $attribute, $event): bool
    {
        
        return in_array($attribute, self::EVENT_EDIT ,self::EVENT_DELETE)
            && $event instanceof \App\Entity\Event;
    }

    protected function voteOnAttribute(string $attribute, $event, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if( null === $event->getUserId()) return false; 

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EVENT_EDIT:
                return $this->canEdit($event, $user);
                break;
            case self::EVENT_DELETE:
                return $this->canDelete($event, $user);
                break;
        }

        return false;
    }
    private function canEdit(Event $event,User $user){
        return $user === $event->getUserId();
    }
    private function canDelete(Event $event,User $user){
        return $user === $event->getUserId();
    }
}
