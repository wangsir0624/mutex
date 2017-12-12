<?php
namespace Wangjian\Lock\RedisAdapter;

use InvalidArgumentException;
use Predis\Client;
use Redis;

class RedisAdapterFactory {
    /**
     * 创建一个PhpRedisAdpater
     * @param string $dsn  连接字符串，格式为scheme://auth@host:port/db
     * @return PhpRedisAdapter
     */
    public static function createPhpRedisAdapter($dsn) {
        $configure = self::parseDsn($dsn);

        return self::createPhpRedisAdapterFromConfigure($configure);
    }

    /**
     * 利用RedisConfigure对象创建一个PhpRedisAdapter
     * @param RedisConfigure $configure
     * @return PhpRedisAdapter
     */
    public static function createPhpRedisAdapterFromConfigure(RedisConfigure $configure) {
        $client = new Redis();
        $client->connect($configure->host, $configure->port);
        $client->auth($configure->auth);
        $client->select($configure->db);

        return new PhpRedisAdapter($client);
    }

    /**
     * 创建一个PredisAdpater
     * @param string $dsn  连接字符串，格式为scheme://auth@host:port/db
     * @return PredisAdapter
     */
    public static function createPredisAdapter($dsn) {
        $configure = self::parseDsn($dsn);

        return self::createPredisAdapterFromConfigure($configure);
    }

    /**
     * 利用RedisConfigure对象创建一个PredisAdapter
     * @param RedisConfigure $configure
     * @return PredisAdapter
     */
    public static function createPredisAdapterFromConfigure(RedisConfigure $configure) {
        $client = new Client([
            'scheme' => $configure->scheme,
            'host' => $configure->host,
            'port' => $configure->port
        ]);

        $client->auth($configure->auth);
        $client->select($configure->db);

        return new PredisAdapter($client);
    }

    /**
     * 将连接字符串解析为RedisConfigure对象
     * @param string $dsn  连接字符串，格式为scheme://auth@host:port/db
     * @return RedisConfigure
     */
    public static function parseDsn($dsn) {
        $url = parse_url($dsn);
        if(!$url) {
            throw new InvalidArgumentException('invalid dsn');
        }

        $configure = new RedisConfigure();
        if(isset($url['scheme'])) {
            $configure->scheme = $url['scheme'];
        }

        if(isset($url['host'])) {
            $configure->host = $url['host'];
        }

        if(isset($url['port'])) {
            $configure->port = $url['port'];
        }

        if(isset($url['user'])) {
            $configure->auth = $url['user'];
        }

        if(isset($url['path'])) {
            $configure->db = trim($url['path'], '/');
        }

        return $configure;
    }
}