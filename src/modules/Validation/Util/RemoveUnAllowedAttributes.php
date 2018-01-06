<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 06.01.18
 * Time: 11:08
 */

namespace Objex\Validation\Util;


/**
 * Class RemoveUnAllowedAttributes
 * @package Objex\Validation\Util
 */
class RemoveUnAllowedAttributes
{
    /**
     * @param array $attributes
     * @param array $definition
     * @return array
     */
    public static function remove(array $attributes, array $definition): array
    {
        $return = [];

        foreach ($definition as $attribute => $spec) {
            if (array_key_exists($attribute, $attributes)) {
                $return[$attribute] = $attributes[$attribute];
            }
        }

        return $return;
    }
}