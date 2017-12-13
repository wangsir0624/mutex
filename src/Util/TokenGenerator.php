<?php
namespace Wangjian\Lock\Util;

/**
 * 生成随机token
 * Class TokenGenerator
 * @package Wangjian\Lock\Util
 */
trait TokenGenerator {
    /**
     * token
     * @var string
     */
    protected $token;

    /**
     * 设置token
     * @param string $token
     */
    public function setToken($token) {
        $this->token = $token;
    }

    /**
     * 获取token
     * @return string
     */
    public function getToken() {
        return $this->token;
    }

    /**
     * 刷新token的值
     *
     */
    public function refreshToken() {
        $guid = uniqid('', true);

        $this->token = md5($guid);
    }
}