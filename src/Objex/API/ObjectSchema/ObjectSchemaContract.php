<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 16:28
 */

namespace Objex\API\ObjectSchema;


interface ObjectSchemaContract
{
    /**
     * @param string $namespace
     * @param array $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(string $namespace, array $data = []);

    /**
     * @param $namespace
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($namespace);
}