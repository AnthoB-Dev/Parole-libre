<?php

namespace App\Twig;

use App\Repository\CategoryRepository;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
   private $categoryRepository;

   public function __construct(CategoryRepository $categoryRepository)
   {
       $this->categoryRepository = $categoryRepository;
   }

   public function getFunctions(): array
   {
      return [
         new TwigFunction('getCategories', [$this, 'getCategories']),
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
}
