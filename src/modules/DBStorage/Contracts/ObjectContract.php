<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 20:01
 */

namespace Objex\DBStorage\Contracts;


interface ObjectContract
{
    /**
     * @param string $namespace
     * @param array $data
     * @return object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(string $namespace, array $data = []);

    /**
     * @param string $namespace
     * @param int $objectId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(string $namespace, int $objectId);
}