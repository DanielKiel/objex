<?php
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
            ->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\BaseObject')
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
            ->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\BaseObject')
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
            ->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\BaseObject')
            ->bulkObjects($namespace, $data);
    }
}