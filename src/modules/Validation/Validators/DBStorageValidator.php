<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 10:12
 */

namespace Objex\Validation\Validators;


use Doctrine\ORM\Event\LifecycleEventArgs;
use Objex\Validation\Contracts\DBStorageClassValidatorInterface;
use Objex\Validation\Exceptions\ValidationException;

class DBStorageValidator
{
    /**
     * @var LifecycleEventArgs
     */
    private $args;

    private $scenario;

    public function __construct(LifecycleEventArgs $args, $scenario = null)
    {
        $this->args = $args;

        $this->scenario = $scenario;
    }

    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public function validate()
    {
        $entity = $this->args->getObject();

        try {
            $validatorClass = $this->getValidationByObjectResolver($entity);
        }
        catch(\Exception $e) {

            throw new \Exception(
                'miss-configured validations configuration',
                [$e->getTrace()]
            );
        }

        if ($validatorClass === false) {
            return;
        }

        $validator = new $validatorClass($entity);

        if (! $validator instanceof DBStorageClassValidatorInterface) {
            throw new \Validation(
                'invalid class map. ' . $validatorClass . ' must implement DBStorageClassValidatorInterface'
            );
        }

        $errors = $validator->handle($this->scenario);

        if (!empty($errors)) {
            throw new ValidationException($errors);
        }
    }

    /**
     * @param $entity
     * @return bool|string
     * @throws \Exception
     */
    protected function getValidationByObjectResolver($entity)
    {
        $map = objex()->get('config')->getConfig('validations')['map'];

        if (! array_key_exists(get_class($entity), $map)) {
            return false;
        }

        return $map[get_class($entity)];
    }
}