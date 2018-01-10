<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 10.01.18
 * Time: 08:16
 */

namespace Objex\Validation\Validators\Types;


use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionLanguageValidator
{
    private $language;

    public function __construct(ExpressionLanguage $language)
    {
        $this->language = $language;
    }

    /**
     * @param string $rule
     * @param string $attribute
     * @param $value
     * @return bool
     */
    public function validate(string $rule, string $attribute, $value): bool
    {
        return $this->language->evaluate(
            $rule,
            array(
                $attribute => $value,
            )
        );
    }
}