<?php


use Monolog\Level;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;



// create a log channel
$log = new Logger('name');
$log->pushHandler(new StreamHandler('config/log', Level::Debug));

// add records to the log
$log->warning('Foo');
$log->error('Bar');
