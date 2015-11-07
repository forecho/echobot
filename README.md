# echobot
echo 机器人

## 使用

**生成配置文件 然后修改 `urls` 配置**

```
cp config.php.bak config.php
```

**添加 corn 计划**

编辑
```
crontab -e
```

添加下面代码（记得修改把 xx 修改成自己的绝对路径）- 每天8 : 00至23 : 00之间每隔30分钟执行一次

```
0,30 8-23 * * * /xx/xx/php /xx/echobot/index.php
```
