<?php
namespace Wangjian\Lock\RedisAdapter;

abstract class RedisAdapter {
    abstract public function setnx($key, $value);

    abstract public function get($key);

    abstract public function expire($key, $expire);

    abstract public function exists($key);

    abstract public function del($key);
}