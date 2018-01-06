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
use Objex\Models\ObjectSchema;
use Objex\Validation\Rules\StringExpressionLanguageProvider;
use Objex\Validation\Util\RemoveUnAllowedAttributes;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

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
            $this->validateOnlyType($entity->getData(), $definition);

            return;
        }

        if ($validationType === 'min') {
            $this->validateMinType($entity->getData(), $definition);

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

    public function validateOnlyType(array $attributes, array $definition = [])
    {
        //cause only is set, we remove all attributes which are not specified
        $attributes = RemoveUnAllowedAttributes::remove($attributes, $definition);
    }

    public function validateMinType(array $attributes, array $definition = [])
    {

    }

    /**
     * @param array $attributes
     * @param array $definition
     * @return array
     */
    public function validate(array $attributes, array $definition = []): array
    {
        $language = new ExpressionLanguage(null, [
            new StringExpressionLanguageProvider()
        ]);

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