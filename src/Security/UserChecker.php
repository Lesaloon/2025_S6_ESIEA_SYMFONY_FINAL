<?php
namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {

		// Check if the user is an instance of User
		if (!$user instanceof User) {
            return;
        }

        if (!$user->isApproved()) {
            throw new CustomUserMessageAccountStatusException('Your user account is not approved yet.');
		}
		if ($user->isLocked()) {
			throw new CustomUserMessageAccountStatusException('Your user account is locked.');
		}
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // Additional checks after authentication, if needed
    }
}
