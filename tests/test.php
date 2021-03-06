<?php
require_once __DIR__ . '/../vendor/autoload.php';

//使用FlockMutex
/*$fd = fopen(__DIR__ . '/lock', 'r');
$lock = new \Wangjian\Lock\Mutex\FlockMutex($fd);*/

//使用PredisMutex
/*$adapter = \Wangjian\Lock\DistributedAdapter\AdapterFactory::createAdapter('redis://root@127.0.0.1:6379/1');
$lock = new \Wangjian\Lock\Mutex\DistributedMutex($adapter, 'test_lock', 0);*/

//使用信号量互斥锁
$key = ftok(__FILE__, 'l');
$sem = sem_get($key);
$lock = new \Wangjian\Lock\Mutex\SemaphoreMutex($sem);

$lock->synchronized(function() {
   for($i = 0; $i < 10; $i++) {
       echo 1 . PHP_EOL;
       sleep(1);
   }
});