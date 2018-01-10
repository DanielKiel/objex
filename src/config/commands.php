<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 15:45
 */

return [
    \Symfony\Component\Console\Command\HelpCommand::class,
    \Symfony\Component\Console\Command\ListCommand::class,
    \Objex\Core\Console\Commands\Debug\Listener::class,
    \Objex\Core\Console\Commands\Debug\Services::class,

    \Objex\DBStorage\Console\Commands\EvaluateBasicAPIPerformance::class
];