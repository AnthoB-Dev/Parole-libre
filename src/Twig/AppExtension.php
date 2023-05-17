<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
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
