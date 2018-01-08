<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 12:38
 */

namespace Objex\DBStorage\Repositories;


use Doctrine\ORM\EntityRepository;
use Objex\DBStorage\Repositories\Traits\Pagination;
use Objex\DBStorage\Contracts\ObjectSchemaContract;
use Objex\DBStorage\Models\ObjectSchema;

class ObjectSchemaRepository extends EntityRepository implements ObjectSchemaContract
{
    use Pagination;

    /**
     * @param string $namespace
     * @param array $data
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(string $namespace, array $data = [])
    {
        $schema = $this->findOneBy(['name' => $namespace]);

        if (! $schema instanceof ObjectSchema) {
            $schema = new ObjectSchema();
        }

        $schema->setName($namespace);
        $schema->setData($data);

        $this->getEntityManager()->persist($schema);

        try {
            $this->getEntityManager()->flush();
        }
        catch (\Exception $e) {
            //2TODO think about it
            dump($e);
        }

    }

    /**
     * @param $namespace
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($namespace)
    {
        $schema = $this->findOneBy(['name' => $namespace]);

        if (! $schema instanceof ObjectSchema) {
            //@TODO what to do here
        }

        $this->getEntityManager()->remove($schema);

        try {
            $this->getEntityManager()->flush();
        }
        catch (\Exception $e) {
            //2TODO think about it
            dump($e);
        }
    }
}