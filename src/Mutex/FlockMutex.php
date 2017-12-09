<?php
namespace Wangjian\Lock\Mutex;

use Wangjian\Lock\Exception\LockFailedException;
use Wangjian\Lock\Exception\UnlockFailedException;

class FlockMutex extends LockMutex {
    protected $fd;

    public function __construct($fd) {
        if(!is_resource($fd)) {
            throw new \InvalidArgumentException('the file handler must be resource type');
        }

        if(get_resource_type($fd) != 'stream') {
            throw new \InvalidArgumentException('the file handler must be resource type of stream');
        }

        $this->fd = $fd;
    }

    public function lock() {
        if(!flock($this->fd, LOCK_EX)) {
            throw new LockFailedException('lock failed');
        }
    }

    public function unlock() {
        if(!flock($this->fd, LOCK_UN)) {
            throw new UnlockFailedException('unlock failed');
        }
    }
}