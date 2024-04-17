<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArticleVoter extends Voter
{
    public const EDIT = 'ARTICLE_EDIT';
    public const VIEW = 'ARTICLE_VIEW';
    public const DELETE = 'ARTICLE_DELETE';

    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Article;
    }

    /**
     * @param Article|null $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        if (!$subject instanceof Article) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                if ($subject->getUser()->getId() === $user->getId()) {
                    return true;
                }
                break;

            case self::VIEW:
                return true;
                break;

            case self::DELETE:
                return $this->security->isGranted("ROLE_ADMIN");
                break;
        }

        return false;
    }
}
