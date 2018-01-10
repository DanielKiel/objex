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

        try {
            $schema = objex()->get('cache.DBStorage')
                ->getItem('schema.' . encodeNamespace($namespace));
        }
        catch(\exception $e) {
            $schema = objex()->get('cache.DBStorage')
                ->getItem('schema.' . encodeNamespace($namespace), $defaultValue = objex()->get('DBStorage')
                    ->getRepository('Objex\DBStorage\Models\ObjectSchema')
                    ->findOneBy(['name' => $namespace]));
        }
        finally {
            $schema = objex()->get('DBStorage')
                ->getRepository('Objex\DBStorage\Models\ObjectSchema')
                ->findOneBy(['name' => $namespace]);
        }

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
        //it will be the alias which must be refactored to a valid Namespace
        $namespace = decodeNamespace($namespace);

        objex()->get('cache.DBStorage')
            ->deleteItem('schema.' . encodeNamespace($namespace));

        $object = objex()->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\ObjectSchema')
            ->save($namespace, $schema);

        return objex()->get('cache.DBStorage')
            ->getItem('schema.' . encodeNamespace($namespace), $object, [
                'schema'
            ]);
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

        objex()->get('cache.DBStorage')
            ->deleteItem('schema.' .encodeNamespace($namespace));

        return  $schema = objex()->get('DBStorage')
            ->getRepository('Objex\DBStorage\Models\ObjectSchema')
            ->delete($namespace);
    }
}

if (! function_exists('encodeNamespace')) {
    /**
     * @param $namespace
     * @return mixed|null|string|string[]
     */
    function encodeNamespace($namespace) {
        if (! ctype_lower($namespace)) {
            $namespace = preg_replace('/\s+/u', '', ucwords($namespace));
            $namespace = strtolower(preg_replace('/(.)(?=[A-Z])/u', '$1-', $namespace));
            $namespace = str_replace('\\-', '_', $namespace);
        }

        return $namespace;
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