<?php
namespace Wangjian\Lock\Mutex;

use Wangjian\Lock\Exception\UnlockFailedException;
use Wangjian\Lock\Mutex\LockMutex;
use Wangjian\Lock\Util\Loop;

/**
 * 自旋锁
 * Class SpinlockMutex
 * @package Wangjian\Lock\Mutex
 */
abstract class SpinlockMutex extends LockMutex {
    /**
     * 超时时间，0表示不进行超时检测
     * @var int
     */
    protected $timeout;

    /**
     * 循环
     * @var Loop
     */
    protected $loop;

    /**
     * SpinlockMutex constructor
     * @param int $timeout  超时时间，0表示不进行超时检测
     */
    public function __construct($timeout = 0) {
        $this->timeout = $timeout;
        $this->loop = new Loop($timeout);
    }

    /**
     * 获取互斥锁
     */
    public function lock() {
        $this->loop->loop([$this, 'acquire']);
    }

    /**
     * 解锁
     * @throws UnlockFailedException
     */
    public function unlock() {
        if(!$this->release()) {
            throw new UnlockFailedException('lock release failed');
        }
    }

    /**
     * 尝试获取互斥锁
     * @return bool  获取成功，返回true，反之返回false
     */
    abstract public function acquire();

    /**
     * 解锁
     * @return bool  解锁成功，返回true，反之返回false
     */
    abstract public function release();
}