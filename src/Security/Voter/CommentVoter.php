<?php

namespace App\Security\Voter;

use App\Entity\ArticleComment;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class CommentVoter extends Voter
{
    public const EDIT = 'COMMENT_EDIT';
    public const VIEW = 'COMMENT_VIEW';
    public const DELETE = 'COMMENT_DELETE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\ArticleComment;
    }

    /**
     * @param ArticleComment|null $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }
        
        if (!$subject instanceof ArticleComment) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                if ($subject->getUser()->getId() === $user->getId()) {
                    return true;
                } else if ($this->security->isGranted("ROLE_ADMIN")) {
                    return true;
                }
                break;

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;

            case self::DELETE:
                return $this->security->isGranted("ROLE_ADMIN");
                break;
        }

        return false;
    }
}
