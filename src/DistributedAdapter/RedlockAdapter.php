<?php
namespace Wangjian\Lock\DistributedAdapter;

use SplObjectStorage;
use Wangjian\Lock\Mutex\DistributedMutex;

class RedlockAdapter extends Adapter {
    protected $adapters;

    public function __construct(array $adapters) {
        foreach($adapters as $adapter) {
            $this->adapters->attach($adapter);
        }
    }

    public function acquire(DistributedMutex $mutex) {

    }

    public function release(DistributedMutex $mutex) {

    }
}