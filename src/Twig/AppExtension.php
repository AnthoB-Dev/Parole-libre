<?php

namespace App\Twig;

use App\Entity\User;
use App\Entity\Article;
use App\Entity\ArticleLike;
use App\Entity\CommentLike;
use App\Repository\ArticleLikeRepository;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\CommentLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
   private $categoryRepository;
   private $articleRepository;
   private $entityManager;
   private $security;

   public function __construct(
      EntityManagerInterface $entityManager, 
      Security $security, 
      CategoryRepository $categoryRepository, 
      CommentLikeRepository $commentLikeRepository,
      ArticleRepository $articleRepository)
   {
      $this->categoryRepository = $categoryRepository;
      $this->articleRepository = $articleRepository;
      $this->entityManager = $entityManager;
      $this->security = $security;
   }

   public function getFunctions(): array
   {
      return [
         new TwigFunction('getCategories', [$this, 'getCategories']),
         new TwigFunction('isArticleLiked', [$this, 'isArticleLiked']),
         new TwigFunction('isCommentLiked', [$this, 'isCommentLiked']),
         new TwigFunction('getArticleLikesCount', [$this, 'getArticleLikesCount']),
         new TwigFunction('getArticleCommentsCount', [$this, 'getArticleCommentsCount']),
      ];
   }

   public function getCategories(): array
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

   public function getArticleLikesCount(int $articleId): int
   {
      $articleRepository = $this->entityManager->getRepository(Article::class);
      $article = $articleRepository->findOneBy(["id" => $articleId]);
      
      $articleLikesCount = $article->getArticleLikesCount();

      return $articleLikesCount;      
   }

   public function getArticleCommentsCount(int $articleId): int
   {
      $articleRepository = $this->entityManager->getRepository(Article::class);
      $article = $articleRepository->findOneBy(["id" => $articleId]);
      
      $articleCommentsCount = $article->getArticleCommentsCount();

      return $articleCommentsCount;      
   }
}
