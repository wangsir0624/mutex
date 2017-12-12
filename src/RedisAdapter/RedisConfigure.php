<?php
namespace Wangjian\Lock\RedisAdapter;

/**
 * redis连接配置
 * Class RedisConfigure
 * @package Wangjian\Lock\RedisAdapter
 */
class RedisConfigure {
    /**
     * scheme
     * @var string
     */
    public $scheme = 'tcp';

    /**
     * host
     * @var string
     */
    public $host = '127.0.0.1';

    /**
     * port
     * @var string
     */
    public $port = '6379';

    /**
     * password
     * @var string
     */
    public $auth = '';

    /**
     * the database name
     * @var int
     */
    public $db = 0;
}