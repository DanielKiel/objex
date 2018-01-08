<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 08.01.18
 * Time: 15:03
 */

namespace Objex\Logger;


use Monolog\Logger as LoggerService;

class Logger
{
    public $logger;

    public function __construct($name, $handler)
    {
        $this->logger = new LoggerService($name);
        $this->logger->pushHandler($handler);
    }

    public function logException(\Exception $e)
    {
        $this->logger->error($e->getMessage(), [
            $e->getTrace()
        ]);
    }
}