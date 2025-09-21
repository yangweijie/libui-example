<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Area;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Callback Fixed Test", 400, 300);

// 创建 Area 组件
$area = new Area();

// 设置绘制回调
$area->onDraw(function ($drawParams) {
    echo "Draw callback called\n";
});

// 设置键盘事件回调
$area->onKey(function ($keyEvent) use ($app) {
    echo "Key event: " . $keyEvent->Key . "\n";

    if ($keyEvent->Key == 27) { // ESC key
        $app->quit();
        return 1;
    }

    return 1;
});

// 设置窗口内容
$window->setContent($area->getControl());

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    echo "Window closing\n";
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

echo "Window shown, press ESC to exit\n";

// 启动应用循环
$app->run();

echo "Application exited\n";