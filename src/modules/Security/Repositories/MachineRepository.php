<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 11:49
 */

namespace Objex\Security\Repositories;


use Doctrine\ORM\EntityRepository;
use Objex\Security\Models\Machine;

class MachineRepository extends EntityRepository
{
    public function save($name)
    {
        $machine = new Machine();

        $plainApiKey = bin2hex(random_bytes(16));

        $machine->setUsername($name);
        $machine->setApiKey($plainApiKey);

        $this->getEntityManager()->persist($machine);

        $this->getEntityManager()->flush();

        return $machine;
    }
}