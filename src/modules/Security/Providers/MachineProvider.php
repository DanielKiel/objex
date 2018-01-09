<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 09:51
 */

namespace Objex\Security\Providers;


use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class MachineProvider implements UserProviderInterface
{
    public function loadUserByUsername($username)
    {
        return objex()->get('DBStorage')
            ->getRepository('Objex\Security\Models\Machine')
            ->findOneBy(['apiKey', $username]);
    }

    public function refreshUser(UserInterface $user)
    {
        // TODO: Implement refreshUser() method.
    }

    public function supportsClass($class)
    {
        // TODO: Implement supportsClass() method.
    }
}