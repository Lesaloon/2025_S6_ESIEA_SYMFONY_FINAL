<?php
namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        // Example: Block users marked as inactive
        // if (!$user->isActive()) {
        //     throw new CustomUserMessageAccountStatusException('Your user account is inactive.');
        // }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Additional checks after authentication, if needed
    }
}
