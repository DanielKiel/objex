<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 10:21
 */

return [
    'map' => [
        \Objex\DBStorage\Models\BaseObject::class => \Objex\Validation\Validators\BaseObjectValidator::class,
        \Objex\DBStorage\Models\ObjectSchema::class => \Objex\Validation\Validators\ObjectSchemaValidator::class
    ]
];