<?php
echo "00";
// 定义应用目录
define('APP_PATH', __DIR__.'./app/');
// 开启调试模式
define('APP_DEBUG', true);
echo "22";
// 加载框架引导文件
require  __DIR__.'./thinkphp/start.php';