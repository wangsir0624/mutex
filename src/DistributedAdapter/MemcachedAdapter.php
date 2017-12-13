<?php
namespace Wangjian\Lock\DistributedAdapter;

use Memcache;
use Wangjian\Lock\Mutex\DistributedMutex;

class MemcachedAdapter extends Adapter {
    /**
     * memcached client
     * @var Memcache
     */
    protected $client;

    public function __construct(Memcached $client) {
        $this->client = $client;
    }

    public function acquire(DistributedMutex $mutex) {
        //生成随机token，防止别的进程解锁
        $mutex->refreshToken();

        return $this->client->add($mutex->getKey(), $mutex->getToken(), $mutex->getMaxLifeTime());
    }

    public function release(DistributedMutex $mutex) {
        if($this->client->get($mutex->getKey()) !== $mutex->getToken()) {
            return false;
        }

        return $this->client->delete($mutex->getKey(), 0);
    }
}