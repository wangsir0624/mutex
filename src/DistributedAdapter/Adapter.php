<?php
namespace Wangjian\Lock\DistributedAdapter;

use Wangjian\Lock\Mutex\DistributedMutex;

abstract class Adapter {
    /**
     * 尝试获取锁
     * @param DistributedMutex $mux
     * @return bool
     */
    abstract public function acquire(DistributedMutex $mux);

    /**
     * 尝试释放锁
     * @param DistributedMutex $mutex
     * @return bool
     */
    abstract public function release(DistributedMutex $mutex);
}