<?php
namespace Wangjian\Lock\Mutex;

use Wangjian\Lock\DistributedAdapter\Adapter;
use Wangjian\Lock\Util\TokenGenerator;

/**
 * 用Predis库实现的分布式锁
 * Class PredisMutex
 * @package Wangjian\Lock\Mutex
 */
class DistributedMutex extends SpinlockMutex {
    use TokenGenerator;

    const PREFIX = 'lock_';

    /**
     * 锁名称
     * @var string
     */
    protected $key;

    /**
     * distributed adapter
     * @var Adapter
     */
    protected $adapter;

    /**
     * 锁最大生存期
     * @var int
     */
    protected $maxLifetime;

    /**
     * PredisMutex constructor
     * @param Adapter $adapter
     * @param string $key  锁名称
     * @param int $timeout  超时时间，0表示关闭超时检测
     * @param int $maxLifetime  锁最大生存时间，防止程序异常退出时，锁得不到释放的问题
     */
    public function __construct(Adapter $adapter, $key, $timeout = 0, $maxLifetime = 30) {
        parent::__construct($timeout);
        $this->key = self::PREFIX . $key;
        $this->adapter = $adapter;
        $this->maxLifetime = $maxLifetime;
    }

    /**
     * 尝试获取互斥锁
     * @return bool  获取成功，返回true，反之返回false
     */
    public function acquire() {
        //生成随机token，防止别的进程解锁
        $this->refreshToken();

        return $this->adapter->acquire($this);
    }

    /**
     * 释放锁
     * @return bool  解锁成功，返回true，反之返回false
     */
    public function release() {
        return $this->adapter->release($this);
    }

    /**
     * 获取锁名称
     * @return string
     */
    public function getKey() {
        return $this->key;
    }

    /**
     * 获取锁的最大生存时间
     * @return int
     */
    public function getMaxLifeTime() {
        return $this->maxLifetime;
    }
}