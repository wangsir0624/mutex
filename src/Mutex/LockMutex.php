<?php
namespace Wangjian\Lock\Mutex;

abstract class LockMutex extends Mutex {
    abstract public function lock();

    abstract public function unlock();

    public function synchronized(callable $callable) {
        $this->lock();

        try {
            return call_user_func($callable);
        } catch(\Exception $e) {
            throw $e;
        } finally {
            $this->unlock();
        }
    }
}