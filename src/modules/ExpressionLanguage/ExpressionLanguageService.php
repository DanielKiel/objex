<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 14:43
 */

namespace Objex\ExpressionLanguage;



use Objex\Core\Events\Booting;
use Objex\ExpressionLanguage\Specifications\StringExpressionLanguageProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ExpressionLanguageService implements EventSubscriberInterface
{
    /**
     * we register at booting to register our global doctrine subscriber here
     * @param Booting $event
     * @throws \Exception
     */
    public function onBooting(Booting $event)
    {
        $event->getServiceContainer()
            ->set('objex.language', new ExpressionLanguage(null, [
                new StringExpressionLanguageProvider()
            ]));
    }

    public static function getSubscribedEvents()
    {
        return [
            'booting' => 'onBooting'
        ];
    }
}