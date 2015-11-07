<?php
/**
 * @Author: forecho
 * @Date:   2015-11-07 11:28:57
 * @Last Modified by:   forecho
 * @Last Modified time: 2015-11-07 12:39:21
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
    foreach ($config['urls'] as $key => $value) {
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
 * 注意：服务器需要开通 fopen 配置
 * @param string $word 要写入日志里的文本内容 默认值：空值
 */
function logs($word = '')
{
    $data = date('Ym');
    $fp = fopen("log-{$data}.txt", "a");
    flock($fp, LOCK_EX);
    fwrite($fp, "执行日期：" . strftime("%Y%m%d%H%M%S", time()) . "\n" . $word . "\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}


outgoing(['text' => 'test']);
echo '$_POST接收:<br/>';
logs('$_POST接收:' . json_encode($_POST));
print_r($_POST);
echo '<hr/>';

echo 'php://input接收:<br/>';
$data = file_get_contents('php://input');
logs('php://input接收:' . json_encode($data));
print_r($data); 


