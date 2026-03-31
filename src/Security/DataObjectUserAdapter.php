<?php
// src/Security/DataObjectUserAdapter.php
namespace App\Security;

use App\Model\DataObject\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class DataObjectUserAdapter implements UserInterface, PasswordAuthenticatedUserInterface
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): ?string
    {
        return $this->user->getPassword(); // Pimcore hashed password field
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUserIdentifier(): string
    {
        // Use email field from your Pimcore User object
        return $this->user->getUsername(); 
    }

    public function getUsername(): string
    {
        // Backward compatibility
        return $this->getUserIdentifier();
    }

    public function eraseCredentials(): void
    {
        // Nothing to do here
    }
}