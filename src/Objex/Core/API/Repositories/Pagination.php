<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 12:05
 */

namespace Objex\Core\API\Repositories;


use Doctrine\ORM\Tools\Pagination\Paginator;

trait Pagination
{
    /**
     * @TODO think about this trait - how to make it more comfortable, what about fetchJoinCollection?
     * @param $dql
     * @param $from
     * @param $to
     * @return Paginator
     */
    public function paginate($dql, $from, $to)
    {
        $qeuery = $this->getEntityManager()
            ->createQuery($dql)
            ->setFirstResult($from)
            ->setMaxResults($to);

        return new Paginator($qeuery, $fetchJoinCollection = false);
    }
}