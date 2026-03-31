<?php
namespace App\Model\DataObject;

use Pimcore\Model\DataObject\ClassDefinition\Data\Password;
use Pimcore\Model\DataObject\User as BaseUser;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


class User extends BaseUser implements UserInterface, PasswordAuthenticatedUserInterface
{

    public function eraseCredentials(): void {
        /**
         * @var Password $field
         */
        $field = $this->getClass()->getFieldDefinition( 'password' );
        $field->getDataForResource( $this->getPassword(), $this );
    }

    public function getRoles(): array {
        return [ 'ROLE_USER' ];
    }

    public function getUserIdentifier(): string {
        return $this->getUsername();
    }

}