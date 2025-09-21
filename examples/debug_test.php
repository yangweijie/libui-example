<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Area;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Debug Test", 400, 300);

// 测试 1: 只创建 Area，不设置回调
echo "测试 1: 创建 Area\n";
$area = new Area();
echo "Area 创建成功\n";

// 测试 2: 设置空回调
echo "测试 2: 设置空回调\n";
$area->onDraw(function ($drawParams) {
    // 不做任何事情
});
echo "空回调设置成功\n";

$area->onKey(function ($keyEvent) use ($app) {
    if ($keyEvent->Key == 27) {
        $app->quit();
    }
    return 1;
});
echo "键盘回调设置成功\n";

// 设置窗口内容
$window->setContent($area->getControl());

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    $app->quit();
    return true;
});

// 显示窗口
$window->show();
echo "窗口显示成功\n";

// 启动应用循环
$app->run();
echo "应用退出\n";