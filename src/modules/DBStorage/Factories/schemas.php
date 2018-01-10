<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 14:43
 */

if (! function_exists('getSchema')) {
    /**
     * @param string $namespace
     * @return \Objex\DBStorage\Models\ObjectSchema
     * @throws Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    function getSchema(string $namespace) {
        //it will be the alias which must be refactored to a valid Namespace
        $namespace = decodeNamespace($namespace);

        $schema = objex()->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\ObjectSchema')
            ->findOneBy(['name' => $namespace]);

        if (! $schema instanceof \Objex\DBStorage\Models\ObjectSchema) {
            //@TODO make some clear Exceptions
            throw new \Exception('no schema defined for' . $namespace);
        }

        return $schema;
    }
}

if (! function_exists('setSchema')) {
    /**
     * @param string $namespace
     * @param array $schema
     * @return mixed
     * @throws Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    function setSchema(string $namespace, array $schema) {
        return  $schema = objex()->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\ObjectSchema')
            ->save($namespace, $schema);
    }
}

if (! function_exists('deleteSchema')) {
    /**
     * @param string $namespace
     * @return mixed
     * @throws Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    function deleteSchema(string $namespace) {
        //it will be the alias which must be refactored to a valid Namespace
        $namespace = decodeNamespace($namespace);
        return  $schema = objex()->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\ObjectSchema')
            ->delete($namespace);
    }
}

if (! function_exists('decodeNamespace')) {
    /**
     * @param $namespace
     * @return mixed|string
     */
    function decodeNamespace($namespace) {
        $namespace = ucwords(str_replace(['-'], ' ', $namespace));
        $namespace = str_replace(' ', '', $namespace);

        $namespace = ucfirst($namespace);
        $namespace = str_replace('_', ' ', $namespace);
        $namespace = str_replace(' ', '\\', ucwords($namespace));

        return $namespace;
    }
}