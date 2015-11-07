<?php
/**
 * @Author: forecho
 * @Date:   2015-11-07 11:28:57
 * @Last Modified by:   forecho
 * @Last Modified time: 2015-11-07 16:53:30
 */

require_once 'curl.php';

/**
 * $data = {
 *     text: "文本",
 *     username: "机器人名称", // 可选
 *     icon_url: "头像地址" // 可选
 * }
 * @param string $data
 * @throws Exception
 */
function outgoing($data = '')
{
    $curl = new Curl;

    if (empty($data['text'])) {
        throw new Exception("Data Error", 1);
    }

    $config = require_once 'config.php';
    foreach ((array)$config['urls'] as $key => $value) {
        $response = $curl->post($value, $vars = ['payload' => json_encode($data)]);
        if ($response->headers['Status-Code'] == 200) {
            echo "{$key} is ok\n";
            logs("{$key} 推送成功");
        } else {
            print_r($response);
            logs("{$key} 推送失败");
        }
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
    case '10900':
        // 每周一早上9点询问
        outgoing(['text' => '这周你打算做什么？']);
        break;

    case '02230':
        // 每周日晚上10点半询问
        outgoing(['text' => '你这周干了些什么，有什么问题卡住了吗？']);
        break;

    default:
        # code...
        break;
}

switch ($currentTime) {
    case '0930':
        // 每天早上9点半询问
        outgoing(['text' => '你今天打算做什么？']);
        break;

    case '2200':
        // 每天晚上10点询问
        outgoing(['text' => '你今天做了些什么，目前有什么问题卡住了吗？']);
        break;

    default:
        # code...
        break;
}

if (isset($_POST['text']) && strstr($_POST['text'], 'over')) {
    logs('$_POST接收:' . json_encode($_POST, JSON_UNESCAPED_UNICODE));
//    outgoing(['text' => '目前有什么问题卡住了吗？']);
}

