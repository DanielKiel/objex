<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:28
 */
namespace Objex\Controllers;

use Symfony\Component\HttpFoundation\JsonResponse;

class APIController
{
    /**
     * this is only an example at the moment
     *
     * @param $name
     * @return JsonResponse
     * @throws \InvalidArgumentException
     */
    public function indexAction($name)
    {
        $date = date_create_from_format('Y-m-d H:i:s', '2005-10-15 10:00:00');

        return (new JsonResponse([
            'data' => [
                'name' => $name
            ]
        ]))->setCache([
            'public'        => true,
            'etag'          => 'abcde',
            'last_modified' => $date,
            'max_age'       => 10,
            's_maxage'      => 10,
        ]);
    }
}