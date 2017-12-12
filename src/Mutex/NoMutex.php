<?php
namespace Wangjian\Lock\Mutex;

class NoMutex extends Mutex {
    public function synchronized(callable $callable) {
        return call_user_func($callable);
    }
}