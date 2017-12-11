<?php
require_once __DIR__ . '/../vendor/autoload.php';

//使用FlockMutex
/*$fd = fopen(__DIR__ . '/lock', 'r');
$lock = new \Wangjian\Lock\Mutex\FlockMutex($fd);*/

//使用PredisMutex
$client = new \Predis\Client('tcp://127.0.0.1:6379');
$client->auth('root');
$lock = new \Wangjian\Lock\Mutex\PredisMutex($client, 'test_lock', 3);

$lock->synchronized(function() {
   for($i = 0; $i < 10; $i++) {
       echo 12 . PHP_EOL;
       sleep(1);
   }
});