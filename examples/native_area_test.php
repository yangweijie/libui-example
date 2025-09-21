<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Kingbes\Libui\Area as LibuiArea;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Native Area Test", 400, 300);

// 直接使用 libui 的 Area，不使用封装
$handler = LibuiArea::handler();

// 创建 Area
$area = LibuiArea::create($handler);

// 设置窗口内容
$window->setContent($area);

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

// 启动应用循环
$app->run();