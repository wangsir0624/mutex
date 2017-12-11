<?php
namespace Wangjian\Lock\Mutex;

use Exception;
use Wangjian\Lock\Exception\LockFailedException;

abstract class LockMutex extends Mutex {
    abstract public function lock();

    abstract public function unlock();

    public function synchronized(callable $callable) {
        $this->lock();

        try {
            return call_user_func($callable);
        } finally {
            $this->unlock();
        }
    }
}