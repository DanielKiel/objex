<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 14:43
 */

namespace Objex\ExpressionLanguage;



use Objex\Core\Events\Booting;
use Objex\Core\Modules\Extension;
use Objex\ExpressionLanguage\Specifications\StringExpressionLanguageProvider;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionLanguageExtension extends Extension
{
    /**
     * we register at booting to register our global doctrine subscriber here
     * @param Booting $event
     * @throws \Exception
     */
    public function boot(Booting $event)
    {
        $event->getServiceContainer()
            ->set('objex.language', new ExpressionLanguage(null, [
                new StringExpressionLanguageProvider()
            ]));
    }

}