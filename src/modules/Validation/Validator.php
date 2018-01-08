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
use Objex\DBStorage\Models\ObjectSchema;
use Objex\Validation\Exceptions\ValidationException;
use Objex\Validation\Util\RemoveUnAllowedAttributes;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Validation;

class Validator implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::preRemove
        );
    }

    /**
     * will validate before creating
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof ObjectSchema) {
            return;
        }

        /* @TODO here must be: get definition by schema, then make validation - think about when failing
         * throw own exception to can handle an own validation error return at controller by following event of exception !
         */
        $schema = $entity->getSchema();

        $definition = $schema->getDefinition();

        $validationType = $schema->getValidationType();

        if ($validationType === 'only') {
            $this->performValidationResult(
                RemoveUnAllowedAttributes::remove($entity->getData(), $definition),
                $definition
            );

            return;
        }

        if ($validationType === 'min') {
            $this->performValidationResult($entity->getData(), $definition);

            return;
        }

    }

    /**
     * will validate before updating
     * @param LifecycleEventArgs $args
     */
    public function preUpdate(LifecycleEventArgs $args)
    {

    }

    /**
     * will validate before deleting
     * @param LifecycleEventArgs $args
     */
    public function preRemove(LifecycleEventArgs $args)
    {

    }

    /**
     * @param array $attributes
     * @param array $definition
     */
    public function performValidationResult(array $attributes, array $definition = []): void
    {
        $result = $this->validate($attributes, $definition);

        if (! empty($result)) {
            throw (new ValidationException($result));
        }
    }

    /**
     * @param array $attributes
     * @param array $definition
     * @return array
     */
    public function validate(array $attributes, array $definition = []): array
    {
        $language = objex()->get('objex.language');

        $errors = [];

        foreach ($definition as $attribute => $ruleset) {
            if (! array_key_exists('validation', $ruleset)) {
                continue;
            }

            if (! array_key_exists($attribute, $attributes)) {
                continue;
            }

            $validation = $ruleset['validation'];

            if (is_string($validation)) {
                $result = $this->validateByExpression($language, $validation, $attribute, $attributes[$attribute]);

                if ($result === false) {
                    if (array_key_exists('errormessage', $ruleset)) {
                        $errors[$attribute] = $ruleset['errormessage'];
                    }
                    else {
                        $errors[$attribute] = 'there was an error on validating this attribute';
                    }

                }
            }

            if (is_object($validation) || is_array($validation)) {

                if (is_object($validation) && ! $validation instanceof Constraint) {
                    throw new \Exception('Validation missconfigured, definition of ' . get_class($validation) . ' is not supported');
                }

                $validator = Validation::createValidator();
                $results = $validator->validate($attributes[$attribute], $validation);

                if ($results->count() > 0) {
                    $errors[$attribute] = '';
                    foreach ($results as $error) {
                        $errors[$attribute] .= $error->getMessage();
                    }
                }


            }
        }

        return $errors;
    }

    /**
     * @param ExpressionLanguage $language
     * @param string $rule
     * @param string $attribute
     * @param $value
     * @return bool
     */
    public function validateByExpression(ExpressionLanguage $language, string $rule, string $attribute, $value): bool
    {
        return $language->evaluate(
            $rule,
            array(
                $attribute => $value,
            )
        );
    }
}