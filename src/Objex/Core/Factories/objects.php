<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 14:31
 */
if (! function_exists('objex')) {
    function objex()  {
        return include __DIR__.'/../../../bootstrap/services.php';
    }
}

if (! function_exists('saveObject')) {
    function saveObject($namespace, array $data = []) {
        return objex()
            ->get('orm')
            ->getRepository('Objex\Models\BaseObject')
            ->save($namespace, $data);
    }
}

if (! function_exists('deleteObject')) {
    function deleteObject($namespace, int $id) {
        return objex()
            ->get('orm')
            ->getRepository('Objex\Models\BaseObject')
            ->delete($namespace, $id);
    }
}

if (! function_exists('bulkObjects')) {
    function bulkObjects($namespace, array $data = []) {
        return objex()
            ->get('orm')
            ->getRepository('Objex\Models\BaseObject')
            ->bulkObjects($namespace, $data);
    }
}