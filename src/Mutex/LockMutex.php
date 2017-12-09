<?php
namespace Wangjian\Lock\Mutex;

use Exception;
use Wangjian\Lock\Exception\LockFailedException;

abstract class LockMutex extends Mutex {
    abstract public function lock();

    abstract public function unlock();

    public function synchronized(callable $callable) {
        try {
            $this->lock();

            return call_user_func($callable);
        } catch(Exception $e) {
            if(!($e instanceof LockFailedException)) {
                $this->unlock();
            }

            throw $e;
        }
    }
}