<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 10:28
 */
namespace Objex\Controllers;

use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

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
        $results = objex()
            ->get('orm')
            ->getRepository('Objex\Models\BaseObject')
            ->getAll(Query::HYDRATE_ARRAY);

        return new JsonResponse([
            'data' => $results
        ]);
//        $date = date_create_from_format('Y-m-d H:i:s', '2005-10-15 10:00:00');
//
//        return (new JsonResponse(objex()
//            ->get('orm')
//            ->getRepository('Objex\Models\BaseObject')
//            ->findAll()
//        ))->setCache([
//            'public'        => true,
//            'etag'          => 'abcde',
//            'last_modified' => $date,
//            'max_age'       => 10,
//            's_maxage'      => 10,
//        ]);
    }

    public function postAction(Request $request)
    {
        setSchema('MyNamespace',[
            'definition' => [
                'foo' => [
                    'type' => 'text',
                    'validation' => 'strpos(foo, "needed") !== false and strlen(foo) > 3'
                ],
                'bar' => [
                    'type' => 'text',
                    'validation' => 'strlen(bar) < 3 and strlen(bar) > 0',
                    'errormessage' => 'bar must not have more than 2 signs but is required'
                ]
            ]
        ]);

        $result = saveObject('MyNamespace',$request->request->all());

        return new JsonResponse([
            'data' => $result
        ]);
    }
}