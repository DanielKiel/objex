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
     * @return \Objex\Models\ObjectSchema
     * @throws Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    function getSchema(string $namespace) {
        $schema = objex()->get('orm')
            ->getRepository('Objex\Models\ObjectSchema')
            ->findOneBy(['name' => $namespace]);

        if (! $schema instanceof \Objex\Models\ObjectSchema) {
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
        return  $schema = objex()->get('orm')
            ->getRepository('Objex\Models\ObjectSchema')
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
        return  $schema = objex()->get('orm')
            ->getRepository('Objex\Models\ObjectSchema')
            ->delete($namespace);
    }
}