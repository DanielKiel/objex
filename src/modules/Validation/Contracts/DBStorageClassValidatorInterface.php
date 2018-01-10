<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 10:43
 */

namespace Objex\Validation\Contracts;


interface DBStorageClassValidatorInterface
{
    /**
     * @return array
     */
    public function handle(): array;
}