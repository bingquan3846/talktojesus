<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class HistoryVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const VIEW = 'POST_VIEW';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\History;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();
        $history = $subject;

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            $vote?->addReason('The user must be logged in to access this resource.');

            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                return $this->canEdit($history, $user);
                break;

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                return $this->canView($history, $user);
                break;

            case self::DELETE:
                // logic to determine if the user can DELETE
                // return true or false
                return $this->canDelete($history, $user);
                break;
        }

        return false;
    }

    private function canEdit(\App\Entity\History $history, UserInterface $user): bool
    {
        // Implement your logic to determine if the user can edit the history
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }
        return $user === $history->getUser();
    }
    private function canView(\App\Entity\History $history, UserInterface $user): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }
        // Implement your logic to determine if the user can view the history
        return $user === $history->getUser();
    }
    private function canDelete(\App\Entity\History $history, UserInterface $user): bool
    {
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }   
        // Implement your logic to determine if the user can delete the history
        return $user === $history->getUser();
    }
}
