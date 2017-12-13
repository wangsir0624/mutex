<?php
namespace Wangjian\Lock\DistributedAdapter;

use InvalidArgumentException;
use Predis\Client;
use Redis;
use Memcache;
use Memcached;

class AdapterFactory {
    protected static $createAdapterMaps = [
        'redis' => 'createPhpRedisAdapter',
        'predis' => 'createPredisAdapter',
        'memcache' => 'createMemcacheAdapter',
        'memcached' => 'createMemcachedAdapter'
    ];

    /**
     * 创建一个Adapter
     * @param string|array $dsn  如果为数组，那么创建一个分布式的RedlockAdapter
     * @return Adapter
     */
    public static function createAdapter($dsn) {
        if(empty($dsn)) {
            throw new InvalidArgumentException('connection string can\'t be empty');
        }

        if(is_array($dsn)) {
            $adapters = [];
            foreach($dsn as $item) {
                if($item instanceof Adapter) {
                    $adapters[] = $item;
                } else {
                    $adapters[] = self::parseDsn($item);
                }
            }

            return new RedlockAdapter($adapters);
        } else {
            $configure = self::parseDsn($dsn);

            if (!key_exists($configure->scheme, self::$createAdapterMaps)) {
                throw new InvalidArgumentException('unsupported adapter');
            }

            $createMethod = self::$createAdapterMaps[$configure->scheme];

            return self::$createMethod($configure);
        }
    }

    /**
     * 利用RedisConfigure对象创建一个PhpRedisAdapter
     * @param ConnectionConfigure $configure
     * @return PhpRedisAdapter
     */
    protected static function createPhpRedisAdapter(ConnectionConfigure $configure) {
        $client = new Redis();
        $client->connect($configure->host, $configure->port);
        $client->auth($configure->auth);
        $client->select($configure->db);

        return new PhpRedisAdapter($client);
    }

    /**
     * 利用RedisConfigure对象创建一个PredisAdapter
     * @param ConnectionConfigure $configure
     * @return PredisAdapter
     */
    protected static function createPredisAdapter(ConnectionConfigure $configure) {
        $client = new Client([
            'scheme' => 'tcp',
            'host' => $configure->host,
            'port' => $configure->port
        ]);

        $client->auth($configure->auth);
        $client->select($configure->db);

        return new PredisAdapter($client);
    }

    /**
     * 利用RedisConfigure对象创建一个PredisAdapter
     * @param ConnectionConfigure $configure
     * @return MemcacheAdapter
     */
    protected static function createMemcacheAdapter(ConnectionConfigure $configure) {
        $client = new Memcache();
        $client->connect($configure->host, $configure->port);

        return new MemcacheAdapter($client);
    }

    /**
     * 利用RedisConfigure对象创建一个PredisAdapter
     * @param ConnectionConfigure $configure
     * @return MemcachedAdapter
     */
    protected static function createMemcachedAdapter(ConnectionConfigure $configure) {
        $client = new Memcached();
        $client->addServer($configure->host, $configure->port);

        return new MemcachedAdapter($client);
    }

    /**
     * 将连接字符串解析为RedisConfigure对象
     * @param string $dsn  连接字符串，格式为scheme://auth@host:port/db
     * @return ConnectionConfigure
     */
    public static function parseDsn($dsn) {
        $url = parse_url($dsn);
        if(!$url) {
            throw new InvalidArgumentException('invalid dsn');
        }

        $configure = new ConnectionConfigure();
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