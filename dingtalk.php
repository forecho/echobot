<?php
/**
 * @Author: forecho
 * @Date:   2017-07-22 11:28:57
 * @Last Modified by:   forecho
 * @Last Modified time: 2017-07-22 16:53:30
 */

require_once 'curl.php';

/**
 * $data = {
 *     'msgtype': "文本", //可选
 *     content: "机器人名称", // 消息内容
 * }
 * @param array $data
 * @throws Exception
 */
function outgoing($data = [])
{
    $curl = new Curl;

    if (empty($data['text'])) {
        throw new Exception("Data Error", 1);
    }

    $config = require_once 'config.php';
    $postData = $data + [
            'msgtype' => 'text',
            'at' => [
                'atMobiles' => empty($config['ding']['atMobiles']) ? [] : $config['ding']['atMobiles'],
                'isAtAll' => empty($config['ding']['atMobiles'])
            ]];
    $curl->headers = ['Content-Type' => 'application/json;charset=utf-8'];
    $curl->post($config['ding']['url'], json_encode($postData));
    if (!$curl->error()) {
        echo "dingTalk is ok\n";
        logs("dingTalk 推送成功");
    } else {
        print_r($curl->error());
        logs("dingTalk 推送失败" . $curl->error());
    }
}

/**
 * 写日志，方便测试
 * @param string $word 要写入日志里的文本内容 默认值：空值
 */
function logs($word = '')
{
    $data = date('Ym');
    file_put_contents("logs" . DIRECTORY_SEPARATOR . "log-{$data}.log", "执行日期：" . strftime("%Y%m%d%H%M%S", time()) . "\n" . $word . "\n", FILE_APPEND);
}


//$currentTime = date('YmdHis');
$currentTime = date('Hi');

//w  星期中的第几天，数字表示 0（星期天）到 6（星期六）
$currentDay = date('wHi');

switch ($currentDay) {
    case '51810':
        // 每周五下午6点10分询问
        outgoing(['text' => ['content' => '【周报小助手】各位下班之前记得写周报']]);
        break;

    default:
        # code...
        break;
}

switch ($currentTime) {
    case '0950':
        // 每天早上9点50分询问
        outgoing(['text' => ['content' => '【早会小助手】还有10分钟准备开早会了']]);
        break;

    case '1000':
        // 每天早上10点询问
        outgoing(['text' => ['content' => '【早会小助手】现在开始开早会了']]);
        break;

    default:
        # code...
        break;
}