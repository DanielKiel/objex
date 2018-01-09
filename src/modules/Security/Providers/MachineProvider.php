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
    /**
     * cause we have to follow the interface rules, method name is not the same as real criteria - we ask for username
     * @param string $apiKey
     * @return UserInterface
     * @throws \Exception
     */
    public function loadUserByUsername($apiKey)
    {
        return objex()->get('DBStorage')
            ->getRepository('Objex\Security\Models\Machine')
            ->findOneBy(['apiKey' => $apiKey]);
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