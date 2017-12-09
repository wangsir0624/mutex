<?php
namespace Wangjian\Lock\Util;

use Wangjian\Lock\Exception\TimeoutException;

class Loop {
    /**
     * 超时时间，0表示不进行超时检测
     * @var int
     */
    protected $timeout;

    /**
     * Loop constructor
     * @param int $timeout 超时时间
     */
    public function __construct($timeout = 0) {
        $this->timeout = $timeout;
    }

    /**
     * loop
     * @param callable $until  终止条件，当此函数返回true时，终止循环
     * @return bool
     * @throws TimeoutException
     */
    public function loop(callable $until) {
        $start = microtime(true);
        $end = $start + $this->timeout;

        while(true) {
            $result = call_user_func($until);

            if($result === true) {
                return true;
            }

            //如果开启超时检测，检测是否超时，如果超时，抛出异常
            $now = microtime(true);
            if($this->timeout > 0 && $now > $end) {
                throw new TimeoutException('spin lock timed out');
            }
        }
    }
}