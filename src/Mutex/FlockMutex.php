<?php
namespace Wangjian\Lock\Mutex;

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
        return flock($this->fd, LOCK_EX);
    }

    public function unlock() {
        return flock($this->fd, LOCK_UN);
    }
}