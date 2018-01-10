<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 10:17
 */

namespace Objex\Validation\Validators;


use Objex\DBStorage\Models\ObjectSchema;
use Objex\Validation\Contracts\DBStorageClassValidatorInterface;

class ObjectSchemaValidator implements DBStorageClassValidatorInterface
{
    private $entity;

    public function __construct(ObjectSchema $entity)
    {
        $this->entity = $entity;
    }

    public function handle(): array
    {
        return [];
    }
}