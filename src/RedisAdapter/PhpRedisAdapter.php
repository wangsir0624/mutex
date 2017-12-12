<?php
namespace Wangjian\Lock\RedisAdapter;

use Redis;

class PhpRedisAdapter extends RedisAdapter {
    protected $client;

    public function __construct(Redis $client) {
        $this->client = $client;
    }

    public function setnx($key, $value) {
        return $this->client->setnx($key, $value);
    }

    public function get($key) {
        return $this->client->get($key);
    }

    public function expire($key, $expire) {
        return $this->client->expire($key, $expire);
    }

    public function exists($key) {
        return $this->client->exists($key);
    }

    public function del($key) {
        return $this->client->del($key);
    }

    public function __call($method, $arguments) {
        return call_user_func_array([$this->client, $method], $arguments);
    }
}