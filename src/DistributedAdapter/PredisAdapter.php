<?php
namespace Wangjian\Lock\DistributedAdapter;

use Predis\Client;
use Wangjian\Lock\Mutex\DistributedMutex;

class PredisAdapter extends Adapter {
    /**
     * predis client
     * @var Client
     */
    protected $client;

    public function __construct(Client $client) {
        $this->client = $client;
    }

    public function acquire(DistributedMutex $mutex) {
        //生成随机token，防止别的进程解锁
        $mutex->refreshToken();

        $result = $this->client->setnx($mutex->getKey(), $mutex->getToken()) > 0;
        if($result) {
            $this->client->expire($mutex->getKey(), $mutex->getMaxLifeTime());
        }

        return $result;
    }

    public function release(DistributedMutex $mutex) {
        //如果锁没有被获取，则解锁失败
        if(!$this->client->exists($mutex->getKey())) {
            return false;
        }

        //如果token不一致，解锁失败，防止其他进程解锁
        if($this->client->get($mutex->getKey()) != $mutex->getToken()) {
            return false;
        }

        return $this->client->del($mutex->getKey()) > 0;
    }
}