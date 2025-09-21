<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Area;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Area Callback Test", 400, 300);

// 创建 Area 组件
echo "创建 Area 组件...\n";
$area = new Area();
echo "Area 组件创建完成\n";

// 逐步添加回调
echo "设置绘制回调...\n";
$area->onDraw(function ($drawParams) {
    echo "绘制回调被调用\n";
});
echo "绘制回调设置完成\n";

echo "设置键盘回调...\n";
$area->onKey(function ($keyEvent) use ($app) {
    echo "按键事件: " . $keyEvent->Key . "\n";

    // 按 ESC 键退出
    if ($keyEvent->Key == 27) {
        echo "ESC 键被按下，退出程序\n";
        $app->quit();
        return 1;
    }

    return 1;
});
echo "键盘回调设置完成\n";

// 设置窗口内容
$window->setContent($area->getControl());

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    echo "窗口关闭事件\n";
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

echo "窗口已显示，按 ESC 键退出\n";

// 启动应用循环
$app->run();

echo "应用已退出\n";