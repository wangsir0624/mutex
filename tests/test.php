<?php
require_once __DIR__ . '/../vendor/autoload.php';

$loop = new \Wangjian\Lock\Util\Loop();

$loop->loop(function() {
    return true;
});

echo 1;