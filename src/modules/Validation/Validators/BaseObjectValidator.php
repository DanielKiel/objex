<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 10:17
 */

namespace Objex\Validation\Validators;


use Objex\DBStorage\Models\BaseObject;
use Objex\Validation\Contracts\DBStorageClassValidatorInterface;
use Objex\Validation\Messages\MessageResolver;
use Objex\Validation\Util\RemoveUnAllowedAttributes;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class BaseObjectValidator implements DBStorageClassValidatorInterface
{
    private $entity;

    private $errors = [];

    /** @var null|string */
    private $on;

    public function __construct(BaseObject $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @param null $scenario
     * @return array
     * @throws \Exception
     */
    public function handle($scenario = null): array
    {
        $this->on = $scenario;

        $schema = $this->entity->getSchema();

        $definition = $schema->getDefinition();

        $validationType = $schema->getValidationType();

        $attributes = $this->entity->getData();
        if ($validationType === 'only') {
            $attributes = RemoveUnAllowedAttributes::remove($attributes, $definition);
        }

        return $this->validate($attributes, $definition);
    }

    /**
     * @param array $attributes
     * @param array $definition
     * @return array
     * @throws \Exception
     */
    public function validate(array $attributes, array $definition = []): array
    {
        $this->errors = [];

        foreach ($definition as $attribute => $ruleset) {
            $this->validateRuleSet($ruleset, $attributes, $attribute);
        }

        return $this->errors;
    }

    /**
     * @param array $ruleset
     * @param array $attributes
     * @param string $attribute
     * @throws \Exception
     */
    protected function validateRuleSet(array $ruleset, array $attributes, string $attribute)
    {
        if (! $this->haveToBeValidated($ruleset, $attributes, $attribute)) {
            return;
        }

        $validation = $ruleset['validation'];

        if (is_string($validation)) {
            $this->pushErrors(
                objex()->get('Validation.ExpressionLanguage')->validate($validation, $attribute, $attributes[$attribute]),
                $ruleset,
                $attribute
            );

            return;
        }

        if (is_object($validation) && ! $validation instanceof Constraint) {
            throw new \Exception('Validation missconfigured, definition of ' . get_class($validation) . ' is not supported');
        }

        $this->pushErrors(
            objex()->get('Validation.Constraints')
                ->validate($attributes[$attribute], $validation),
            $ruleset,
            $attribute
        );
    }

    /**
     * @param array $ruleset
     * @param array $attributes
     * @param string $attribute
     * @return bool
     */
    protected function haveToBeValidated(array $ruleset, array $attributes, string $attribute): bool
    {
        if (! array_key_exists('validation', $ruleset)) {
            return false;
        }

        if (! array_key_exists($attribute, $attributes)) {
            //when validation is defined and attribute is not here on create, we must throw an error
            if ($this->on === 'onPrePersist') {
                $this->pushErrors($result = false, $ruleset, $attribute);
            }

            return false;
        }

        return true;
    }

    /**
     * @param bool|ConstraintViolationListInterface $result
     * @param array $ruleset
     * @param string $attribute
     */
    protected function pushErrors($result, array $ruleset, string $attribute): void
    {
        $messageResolver = new MessageResolver($result, $ruleset);

        if ($messageResolver->hasErrors() === false) {
            return;
        }

        $this->errors[$attribute] = $messageResolver->getMessage();
    }
}