<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 09.01.18
 * Time: 15:39
 */

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;

$application = new Application();

foreach (objex()->get('config')->getConfig('commands') as $command) {
    $application->add(new $command);
}

$application->run();