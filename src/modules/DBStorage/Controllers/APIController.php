<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 12:26
 */

namespace Objex\DBStorage\Controllers;


use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class APIController
{
    /**
     * @param $alias
     * @return JsonResponse
     * @throws \Exception
     */
    public function getAction($alias)
    {
        $results = objex()
            ->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\BaseObject')
            ->getAll(Query::HYDRATE_ARRAY);

        return new JsonResponse([
            $results
        ]);
    }

    /**
     * @param Request $request
     * @param $alias
     * @return JsonResponse
     * @throws \Exception
     */
    public function postAction(Request $request, $alias)
    {
        $result = saveObject($alias ,$request->request->all());

        return new JsonResponse([
            $result
        ]);
    }

    /**
     * @param Request $request
     * @param $alias
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function putAction(Request $request, $alias, $id)
    {
        $attributes = $request->request->all();
        $attributes['id'] = $id;

        $result = saveObject($alias, $attributes);

        return new JsonResponse([
            $result
        ]);
    }

    /**
     * @param Request $request
     * @param $alias
     * @return JsonResponse
     * @throws \Exception
     */
    public function bulkAction(Request $request, $alias)
    {
        $data = $request->request->all();

        $result = bulkObjects($alias, $data);

        return new JsonResponse([
            $result
        ]);
    }

    /**
     * @param $alias
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteAction($alias, $id)
    {
        $result = deleteObject($alias, $id);

        return new JsonResponse([
            'result' => $result
        ]);
    }
}