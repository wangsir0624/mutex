<?php
namespace Wangjian\Lock\Mutex;

use Predis\Client;

class PredisMutex extends SpinlockMutex {
    protected $key;

    protected $client;

    public function __construct(Client $client, $key, $timeout = 0) {
        parent::__construct($timeout);
        $this->key = $key;
        $this->client = $client;
    }

    public function acquire() {
        $result = $this->client->setnx($this->key, 11) > 0;
        if($result) {
            $this->client->expire($this->key, $this->timeout);
        }

        return $result;
    }

    public function release() {
        return $this->client->del($this->key) > 0;
    }
}