<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 20:28
 */

namespace Objex\DBStorage\Repositories;


use Doctrine\ORM\EntityRepository;
use Objex\DBStorage\Repositories\Traits\Pagination;
use Objex\DBStorage\Models\BaseObject;
use Objex\DBStorage\Contracts\ObjectContract;

class ObjectRepository extends EntityRepository implements ObjectContract
{
    use Pagination;

    public function getAll($limit)
    {
        $query = 'Select o.data as obj From Objex\\DBStorage\\Models\\BaseObject o  ORDER BY o.id DESC';

        $query = $this->getEntityManager()->createQuery($query);
        $query->setMaxResults($limit);
        return $query->getResult();
    }

    /**
     * @param string $namespace
     * @param array $data
     * @return \stdClass
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function save(string $namespace, array $data = []): \stdClass
    {
        $schema = getSchema($namespace);

        $object = null;
        if (array_key_exists('id', $data)) {
            $object = objex()->get('DBStorage')
                ->getRepository('Objex\DBStorage\Models\BaseObject')
                ->find($data['id']);

            unset($data['id']);
        }

        if (! $object instanceof BaseObject) {
            $object = new BaseObject();
        }

        $object->setToken(hash('sha512', time()));
        $object->setSchema($schema);
        $object->setData($data);

        $object = $this->getEntityManager()->merge($object);

        try {
            $this->getEntityManager()->flush();
        }
        catch(\Exception $e) {
            dump($e);
        }

        return (object) array_merge([
            'id' => $object->getId()
        ], $object->getData());
    }

    public function bulk(string $namespace, array $objects = [], $flushAt = 1000)
    {
        $schema = getSchema($namespace);
        $token = hash('sha512', time());
        $result = [];

        $counter = 0;
        foreach ($objects as $data) {
            $counter++;

            $object = null;
            if (array_key_exists('id', $data)) {
                $object = objex()->get('DBStorage')
                    ->getRepository('Objex\DBStorage\Models\BaseObject')
                    ->find($data['id']);

                unset($data['id']);
            }

            if (! $object instanceof BaseObject) {
                $object = new BaseObject();
            }

            $object->setToken($token);
            $object->setSchema($schema);
            $object->setData($data);

            array_push($result, $this->getEntityManager()->merge($object));

            if ($counter === $flushAt) {
                try {
                    $this->getEntityManager()->flush();
                }
                catch(\Exception $e) {
                    dump($e);
                }
            }
        }

        //there may be a rest
        try {
            $this->getEntityManager()->flush();
        }
        catch(\Exception $e) {
            dump($e);
        }
    }

    /**
     * @param string $namespace
     * @param int $objectId
     * @throws \Doctrine\ORM\ORMException
     */
    public function delete(string $namespace, int $objectId)
    {
        $object = $this->find($objectId);

        if (! $object instanceof BaseObject) {
            //@TODO what to do here
        }

        $this->getEntityManager()->remove($object);

        try {
            $this->getEntityManager()->flush();
        }
        catch (\Exception $e) {
            //2TODO think about it
            dump($e);
        }
    }

}