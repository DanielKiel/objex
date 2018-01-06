<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 06.01.18
 * Time: 11:59
 */

namespace Objex\Validation\Rules;


use Symfony\Component\ExpressionLanguage\ExpressionFunction;
use Symfony\Component\ExpressionLanguage\ExpressionFunctionProviderInterface;

class StringExpressionLanguageProvider implements ExpressionFunctionProviderInterface
{
    public function getFunctions()
    {
        return array(
            ExpressionFunction::fromPhp('strlen'),
            ExpressionFunction::fromPhp('strpos'),
        );
    }
}