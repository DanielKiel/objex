<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 05.01.18
 * Time: 20:03
 */

return [
    \Objex\Core\Middleware\JSONRequest::class,
    \Objex\ExpressionLanguage\ExpressionLanguageExtension::class,
    \Objex\DBStorage\DBStorageExtension::class,
    \Objex\Security\M2MSecurityExtension::class,
    \Objex\Logger\LoggerExtension::class,
    \Objex\Validation\ValidatorExtension::class
];

