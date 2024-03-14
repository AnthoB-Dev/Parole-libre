<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class CustomUserChecker implements UserCheckerInterface
{
   public function checkPreAuth(UserInterface $user): void
   {
      if (!$user instanceof User) {
         return;
      }

      if ($user->isIsBanned()) {
         $banReason = $user->getBanReason();
         // the message passed to this exception is meant to be displayed to the user
         throw new CustomUserMessageAuthenticationException('Votre compte a été banni pour la raison suivante : ' . $banReason->getTitle() . ".");
      }
   }

   public function checkPostAuth(UserInterface $user): void
   {
      // if (!$user instanceof User) {
      //    return;
      // }

      // // user account is expired, the user may be notified
      // if ($user->isExpired()) {
      //    throw new AccountExpiredException('Your user account has expired.');
      // }
   }
}
