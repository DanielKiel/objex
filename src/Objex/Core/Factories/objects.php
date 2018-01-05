<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 14:31
 */
if (! function_exists('objex')) {
    /**
     * @return \Symfony\Component\DependencyInjection\ContainerBuilder
     */
    function objex()  {
        return include __DIR__.'/../../../bootstrap/services.php';
    }
}

if (! function_exists('saveObject')) {
    /**
     * @param string $namespace
     * @param array $data
     * @return mixed
     * @throws Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    function saveObject(string $namespace, array $data = []) {
        return objex()
            ->get('orm')
            ->getRepository('Objex\Models\BaseObject')
            ->save($namespace, $data);
    }
}

if (! function_exists('deleteObject')) {
    /**
     * @param string $namespace
     * @param int $id
     * @return mixed
     * @throws Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    function deleteObject(string $namespace, int $id) {
        return objex()
            ->get('orm')
            ->getRepository('Objex\Models\BaseObject')
            ->delete($namespace, $id);
    }
}

if (! function_exists('bulkObjects')) {
    /**
     * @param string $namespace
     * @param array $data
     * @return mixed
     * @throws Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    function bulkObjects(string $namespace, array $data = []) {
        return objex()
            ->get('orm')
            ->getRepository('Objex\Models\BaseObject')
            ->bulkObjects($namespace, $data);
    }
}