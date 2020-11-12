<?php

namespace dww\workorder;

use dw\workorder\Exceptions\WorkOrderException;
use Ramsey\Uuid\Uuid;

class WorkOrder
{

    private $app_id;
    private $app_secret;
    private $project_id;
    const API_URL = 'http://workorders.test';

    /**
     * WorkOrder constructor.
     * @param $app_id
     * @param $app_secret
     * @param $project_id
     */
    public function __construct($app_id, $app_secret, $project_id)
    {
        $this->app_id = $app_id;
        $this->app_secret = $app_secret;
        $this->project_id = $project_id;
    }

    /**
     * 获取授权登录url
     * @param array $data
     * @return string
     * @throws WorkOrderException
     */
    public function authUrl(array $data)
    {
        $username = $data['username'] ?? '';
        if (blank($username)) {
            throw new WorkOrderException(1, "用户名不能为空");
        }
        if (!preg_match("/^[a-zA-Z]+[a-zA-Z0-9_]*$/i", $username)) {
            throw new WorkOrderException(1, "用户名只能字母开头、可包含，字母，数字，以及下划线 ( _ )");
        }
        $timestamp = time();
        $nonce_str = Uuid::uuid1()->toString();
        $app_id = $this->project_id;
        // 签名字段
        $signData = [
            'app_id' => (string)$app_id,
            'username' => (string)$username,
            'time' => (string)$timestamp,
            'nonce_str' => $nonce_str,
        ];
        $signData = http_build_query($signData);
        $signData = $signData . '&' . $this->app_secret;
        $sign = md5($signData);
        return self::API_URL . "/projects/{$this->project_id}/login?app_id={$app_id}&username={$username}&time={$timestamp}&nonce_str={$nonce_str}&sign={$sign}";
    }
}


