<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 09:24
 */

namespace Objex\Security\Models;


use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Objex\Security\Repositories\MachineRepository")
 * @ORM\Table(name="machines")
 */
class Machine implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $apiKey;

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
    }

    public function getRoles()
    {
        return [
            'ROLE_MACHINE'
        ];
    }


    public function getSalt()
    {
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $name
     */
    public function setUsername($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->name;
    }

    /**
     * @param $apiKey
     * @throws \Defuse\Crypto\Exception\EnvironmentIsBrokenException
     * @throws \Exception
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getApiKey()
    {
        return $this->apiKey;
    }
}