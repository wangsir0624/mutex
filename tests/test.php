<?php
require_once __DIR__ . '/../vendor/autoload.php';

$fd = fopen('./lock', 'r');
$lock = new \Wangjian\Lock\Mutex\FlockMutex($fd);
var_dump($lock->lock());
sleep(10);