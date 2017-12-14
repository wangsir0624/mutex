<?php
namespace Wangjian\Lock\DistributedAdapter;

use Predis\Client;
use Wangjian\Lock\Mutex\DistributedMutex;
use Wangjian\Lock\Util\LuaScript;

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
        $result = $this->client->setnx($mutex->getKey(), $mutex->getToken()) > 0;
        if($result) {
            $this->client->expire($mutex->getKey(), $mutex->getMaxLifeTime());
        }

        return $result;
    }

    public function release(DistributedMutex $mutex) {
        return $this->client->eval(LuaScript::DEL_SCRIPT, 1, $mutex->getKey(), $mutex->getToken());
    }
}