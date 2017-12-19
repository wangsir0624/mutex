<?php
namespace Wangjian\Lock\Util;

use Wangjian\Lock\Exception\TimeoutException;

class Loop {
    /**
     * 最大重试次数，如果超过这个次数还未获取到锁，则进行休眠
     * @var int
     */
    protected $maxTries = 5;

    //随机休眠一段时间，减小脑裂的可能性
    /**
     * 休眠最小微秒数
     * @var int
     */
    protected $minSleep = 50000;

    /**
     * 休眠最大微秒数
     * @var int
     */
    protected $maxSleep = 100000;

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
        $retries = 0;

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

            //如果超过最大重试次数，休眠
            if(++$retries >= $this->maxTries) {
                $retries = 0;
                usleep(rand($this->minSleep, $this->maxSleep));
            }
        }
    }
}