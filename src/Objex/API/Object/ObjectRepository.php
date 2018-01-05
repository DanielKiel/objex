<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 20:28
 */

namespace Objex\API\Object;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Objex\Core\API\Repositories\Pagination;
use Objex\Models\BaseObject;

class ObjectRepository extends EntityRepository implements ObjectContract
{
    use Pagination;

    public function getAll($limit)
    {
        $query = 'Select o.data as obj From Objex\\Models\\BaseObject o  ORDER BY o.id DESC';

        $query = $this->getEntityManager()->createQuery($query);
        $query->setMaxResults($limit);
        return $query->getResult();
    }

    /**
     * @param string $namespace
     * @param array $data
     * @return object
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    public function save(string $namespace, array $data = [])
    {
        $schema = getSchema($namespace);

        $object = null;
        if (array_key_exists('id', $data)) {
            $object = objex()->get('orm')
                ->getRepository('Objex\Models\BaseObject')
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

    /**
     * @param string $namespace
     * @param int $objectId
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
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