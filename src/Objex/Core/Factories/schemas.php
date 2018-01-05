<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 14:43
 */

if (! function_exists('getSchema')) {
    /**
     * @param $namespace
     * @return \Objex\Models\ObjectSchema
     */
    function getSchema($namespace) {
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
    function setSchema($namespace, array $schema) {
        return  $schema = objex()->get('orm')
            ->getRepository('Objex\Models\ObjectSchema')
            ->save($namespace, $schema);
    }
}

if (! function_exists('deleteSchema')) {
    function deleteSchema($namespace) {
        return  $schema = objex()->get('orm')
            ->getRepository('Objex\Models\ObjectSchema')
            ->delete($namespace);
    }
}