<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 20:20
 */

namespace Objex\Validation;


use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Objex\DBStorage\Models\BaseObject;
use Objex\Validation\Exceptions\ValidationException;
use Objex\Validation\Util\RemoveUnAllowedAttributes;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

class Validator implements EventSubscriber
{
    private $errors;

    private $language;

    private $on = '';

    public function __construct()
    {
        /** @var ExpressionLanguage $language */
        $this->language = objex()->get('ExpressionLanguage');
    }

    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove
        );
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws ValidationException
     * @throws \Exception
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->on = 'onPrePersist';
        $this->performValidationResult($args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws ValidationException
     * @throws \Exception
     */
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->on = 'onPreUpdate';
        $this->performValidationResult($args);
    }

    /**
     * @param LifecycleEventArgs $args
     * @throws ValidationException
     * @throws \Exception
     */
    public function preRemove(LifecycleEventArgs $args)
    {
        $this->on = 'onPreRemove';
        $this->performValidationResult($args);
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
     * @param LifecycleEventArgs $args
     * @throws ValidationException
     * @throws \Exception
     */
    protected function performValidationResult(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (! $entity instanceof BaseObject) {
            return;
        }
        $schema = $entity->getSchema();

        $definition = $schema->getDefinition();

        $validationType = $schema->getValidationType();

        $attributes = $entity->getData();
        if ($validationType === 'only') {
            $attributes = RemoveUnAllowedAttributes::remove($attributes, $definition);
        }


        $errors = $this->validate($attributes, $definition);

        if (! empty($errors)) {
            throw (new ValidationException($errors));
        }
    }

    /**
     * @param ExpressionLanguage $language
     * @param string $rule
     * @param string $attribute
     * @param $value
     * @return bool
     */
    protected function validateByExpression(ExpressionLanguage $language, string $rule, string $attribute, $value): bool
    {
        return $language->evaluate(
            $rule,
            array(
                $attribute => $value,
            )
        );
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
                $this->validateByExpression($this->language, $validation, $attribute, $attributes[$attribute]),
                $ruleset,
                $attribute
            );

            return;
        }

        if (is_object($validation) && ! $validation instanceof Constraint) {
            throw new \Exception('Validation missconfigured, definition of ' . get_class($validation) . ' is not supported');
        }

        $this->pushErrors(
            (Validation::createValidator())
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
        if (is_bool($result) && $result === false) {
            if (array_key_exists('errormessage', $ruleset)) {
                $this->errors[$attribute] = $ruleset['errormessage'];
            }
            else {
                $this->errors[$attribute] = 'there was an error on validating this attribute';
            }

            return;
        }

        if ($result instanceof ConstraintViolationListInterface && $result->count() > 0) {
            $this->errors[$attribute] = '';
            foreach ($result as $error) {
                $this->errors[$attribute] .= $error->getMessage();
            }
        }
    }

}