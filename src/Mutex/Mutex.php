<?php
namespace Wangjian\Lock\Mutex;

abstract class Mutex {
    abstract public function synchronized(callable $callable);
}