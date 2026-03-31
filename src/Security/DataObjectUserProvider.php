<?php
// src/Security/DataObjectUserProvider.php
namespace App\Security;

use App\Model\DataObject\User;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class DataObjectUserProvider implements UserProviderInterface
{
    // New method required by Symfony >=5.3
   public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $list = User::getList([
            'filters' => [
                ['property' => 'email', 'operator' => '=', 'value' => $identifier]
            ]
        ]);

        $users = $list->getObjects(); // array of DataObject\User

        if (empty($users)) {
            throw new UserNotFoundException("User '$identifier' not found.");
        }

        return new DataObjectUserAdapter($users[0]);
    }
    // Keep refreshUser
    public function refreshUser(UserInterface $user): UserInterface
    {
        if (!$user instanceof DataObjectUserAdapter) {
            throw new UnsupportedUserException();
        }

        return $this->loadUserByIdentifier($user->getUserIdentifier());
    }

    public function supportsClass(string $class): bool
    {
        return $class === DataObjectUserAdapter::class;
    }

    // Remove loadUserByUsername (deprecated)
}