<?php
namespace Wangjian\Lock\Mutex;

class SemaphoreMutex extends LockMutex {
    protected $semaphore;

    public function __construct($semaphore) {
        if(!is_resource($semaphore)) {
            throw new \InvalidArgumentException('the semaphore must be resource type');
        }

        if(get_resource_type($semaphore) != 'sysvsem') {
            throw new \InvalidArgumentException('the semaphore must be resource type of sysvsem');
        }

        $this->semaphore = $semaphore;
    }

    public function lock() {
        if(!sem_acquire($this->semaphore)) {
            throw new LockFailedException('lock failed');
        }
    }

    public function unlock() {
        if(!sem_release($this->semaphore)) {
            throw new UnlockFailedException('unlock failed');
        }
    }
}