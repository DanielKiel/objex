<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 20:28
 */

namespace Objex\API\Object;


use Doctrine\ORM\EntityRepository;
use Objex\Core\API\Repositories\Pagination;

class ObjectRepository extends EntityRepository implements ObjectContract
{
    use Pagination;

    public function getAll($limit)
    {
        $query = 'Select o From Objex\\Models\\BaseObject o ORDER BY o.id DESC';

        $query = $this->getEntityManager()->createQuery($query);
        $query->setMaxResults($limit);
        return $query->getResult();
    }

}