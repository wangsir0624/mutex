<?php
namespace Wangjian\Lock\Mutex;

use Predis\Client;
use Wangjian\Lock\Util\TokenGenerator;

/**
 * 用Predis库实现的分布式锁
 * Class PredisMutex
 * @package Wangjian\Lock\Mutex
 */
class PredisMutex extends SpinlockMutex {
    use TokenGenerator;

    const PREFIX = 'lock_';

    /**
     * 锁名称
     * @var string
     */
    protected $key;

    /**
     * Predis client
     * @var Client
     */
    protected $client;

    /**
     * 锁最大生存期
     * @var int
     */
    protected $maxLifetime;

    /**
     * PredisMutex constructor
     * @param Client $client  Predis client
     * @param string $key  锁名称
     * @param int $timeout  超时时间，0表示关闭超时检测
     * @param int $maxLifetime  锁最大生存时间，防止程序异常退出时，锁得不到释放的问题
     */
    public function __construct(Client $client, $key, $timeout = 0, $maxLifetime = 30) {
        parent::__construct($timeout);
        $this->key = self::PREFIX . $key;
        $this->client = $client;
        $this->maxLifetime = $maxLifetime;
    }

    /**
     * 尝试获取互斥锁
     * @return bool  获取成功，返回true，反之返回false
     */
    public function acquire() {
        //生成随机token，防止别的进程解锁
        $this->refreshToken();

        $result = $this->client->setnx($this->key, $this->token) > 0;
        if($result) {
            $this->client->expire($this->key, $this->maxLifetime);
        }

        return $result;
    }

    /**
     * 释放锁
     * @return bool  解锁成功，返回true，反之返回false
     */
    public function release() {
        //如果锁没有被获取，则解锁失败
        if(!$this->client->exists($this->key)) {
            return false;
        }

        //如果token不一致，解锁失败，防止其他进程解锁
        if($this->client->get($this->key) != $this->token) {
            return false;
        }

        return $this->client->del($this->key) > 0;
    }
}