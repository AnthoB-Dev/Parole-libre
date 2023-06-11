<?php

namespace App\Helper;

use App\Entity\ArticleLike;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ArticleLikeHelper
{
   private $entityManager;
   private $security;

   public function __construct(EntityManagerInterface $entityManager, Security $security)
   {
      $this->entityManager = $entityManager;
      $this->security = $security;
   }

   public function isArticleLiked(int $articleId): bool
   {
      $currentUser = $this->security->getUser();

      if (!$currentUser instanceof User) {
         return false;
      }

      $articleLikeRepository = $this->entityManager->getRepository(ArticleLike::class);
      $articleLike = $articleLikeRepository->findOneBy([
         'user' => $currentUser,
         'article' => $articleId,
      ]);

      return $articleLike !== null;
   }
}
