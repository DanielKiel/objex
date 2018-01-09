<?php
/**
 * Created by PhpStorm.
 * User: dk
 * Date: 04.01.18
 * Time: 15:36
 */

//$sc = include "src/bootstrap/services.php";

return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet(
    objex()->get('DBStorage')
);