<?php

namespace App\Twig;

use App\Entity\User;
use App\Entity\ArticleLike;
use App\Entity\CommentLike;
use App\Repository\CategoryRepository;
use App\Repository\CommentLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
   private $categoryRepository;
   private $entityManager;
   private $security;

   public function __construct(EntityManagerInterface $entityManager, Security $security, CategoryRepository $categoryRepository, CommentLikeRepository $commentLikeRepository)
   {
      $this->categoryRepository = $categoryRepository;
      $this->entityManager = $entityManager;
      $this->security = $security;
   }

   public function getFunctions(): array
   {
      return [
         new TwigFunction('getCategories', [$this, 'getCategories']),
         new TwigFunction('isArticleLiked', [$this, 'isArticleLiked']),
         new TwigFunction('isCommentLiked', [$this, 'isCommentLiked']),
      ];
   }

   public function getCategories()
   {
      return $this->categoryRepository->findAll();
   }

   public function getFilters(): array
   {
      return [
         // le nom du filtre en twig est 'starts_with'
         new TwigFilter('starts_with', [$this, 'startsWith']),
      ];
   }

   public function startsWith($value, $start): bool
   {
      return strpos($value, $start) === 0;
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

   public function isCommentLiked(int $commentId): bool
   {
      $currentUser = $this->security->getUser();

      if (!$currentUser instanceof User) {
         return false;
      }

      $commentLikeRepository = $this->entityManager->getRepository(CommentLike::class);
      $commentLike = $commentLikeRepository->findOneBy([
         'user' => $currentUser,
         'article_comment' => $commentId,
      ]);

      return $commentLike !== null;
   }
}
