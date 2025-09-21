<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Area;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Final Test", 400, 300);

// 创建 Area 组件
$area = new Area();

// 设置简单的绘制回调
$area->onDraw(function ($drawParams) {
    // 简单的回调，不进行任何操作
    // echo "绘制回调被调用\n"; // 避免过多输出
});

// 设置简单的键盘事件回调
$area->onKey(function ($keyEvent) use ($app) {
    // 简单的回调，只处理 ESC 键
    if ($keyEvent->Key == 27) {
        $app->quit();
        return 1;
    }
    return 1;
});

// 设置窗口内容
$window->setContent($area->getControl());

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

// 启动应用循环
$app->run();