<?php
namespace Wangjian\Lock;

use Wangjian\Lock\Exception\UnlockFailedException;
use Wangjian\Lock\Mutex\LockMutex;
use Wangjian\Lock\Util\Loop;

abstract class SpinlockMutex extends LockMutex {
    protected $key;

    protected $loop;

    public function __construct($key, $timeout = 0) {
        $this->key = $key;
        $this->timeout = $timeout;
        $this->loop = new Loop($timeout);
    }

    public function lock() {
        $this->loop->loop(function() {
            if($this->acquire()) {
                return true;
            }
        });
    }

    public function unlock() {
        if(!$this->release()) {
            throw new UnlockFailedException('lock release failed');
        }
    }

    abstract public function acquire();

    abstract public function release();
}