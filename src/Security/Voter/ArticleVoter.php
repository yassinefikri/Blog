<?php

declare(strict_types=1);

namespace App\Security\Voter;

use App\Entity\Article;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class ArticleVoter extends Voter
{
    /**
     * @param string $attribute
     * @param Article $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === 'POST_EDIT'
            && $subject instanceof Article;
    }

    /**
     * @param string $attribute
     * @param Article $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            'POST_EDIT' => $user === $subject->getPostedBy(),
            default => false,
        };

    }
}
