<?php
namespace Wangjian\Lock\Mutex;

use Wangjian\Lock\Exception\UnlockFailedException;
use Wangjian\Lock\Mutex\LockMutex;
use Wangjian\Lock\Util\Loop;

abstract class SpinlockMutex extends LockMutex {
    protected $timeout;

    protected $loop;

    public function __construct($timeout = 0) {
        $this->timeout = $timeout;
        $this->loop = new Loop($timeout);
    }

    public function lock() {
        $this->loop->loop(function() {
            return $this->acquire();
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