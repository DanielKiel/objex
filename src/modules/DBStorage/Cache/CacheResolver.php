<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 19:49
 */

namespace Objex\DBStorage\CacheResolver;


class CacheResolver
{
    public static $defaultIsReadOptimized = true;

    public static function isReadOptimized()
    {
        $config = objex()->get('config')->getConfig('database');

        if (! array_key_exists('cache', $config)) {
            return self::$defaultIsReadOptimized;
        }

        if (! array_key_exists('optimize', $config['cache'])) {
            return self::$defaultIsReadOptimized;
        }

        if ( $config['cache']['optimize'] === 'read') {
            return true;
        }

        return self::$defaultIsReadOptimized;
    }
}