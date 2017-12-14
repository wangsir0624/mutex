<?php
namespace Wangjian\Lock\DistributedAdapter;

use Redis;
use Wangjian\Lock\Mutex\DistributedMutex;
use Wangjian\Lock\Util\LuaScript;

class PhpRedisAdapter extends Adapter {
    /**
     * redis client
     * @var Redis
     */
    protected $client;

    public function __construct(Redis $client) {
        $this->client = $client;
    }

    public function acquire(DistributedMutex $mutex) {
        $result = $this->client->setnx($mutex->getKey(), $mutex->getToken()) > 0;
        if($result) {
            $this->client->expire($mutex->getKey(), $mutex->getMaxLifeTime());
        }

        return $result;
    }

    public function release(DistributedMutex $mutex) {
        return $this->client->eval(LuaScript::DEL_SCRIPT, [$mutex->getKey(), $mutex->getToken()], 1);
    }
}