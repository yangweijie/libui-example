<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Area;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Minimal Snake Game", 400, 300);

// 创建 Area 组件
$area = new Area();

// 简单的状态变量
$posX = 50;
$posY = 50;

// 设置绘制回调
$area->onDraw(function ($drawParams) use (&$posX, &$posY) {
    echo "绘制回调被调用，位置: $posX, $posY\n";

    // 简单的绘制逻辑，避免复杂操作
    // 这里我们只打印信息，不进行实际绘制以避免段错误
});

// 设置键盘事件回调
$area->onKey(function ($keyEvent) use ($area, &$posX, &$posY, $app) {
    echo "按键事件: " . $keyEvent->Key . "\n";

    // 简单的移动控制
    switch ($keyEvent->Key) {
        case 38: // 上箭头
            $posY -= 10;
            break;
        case 40: // 下箭头
            $posY += 10;
            break;
        case 37: // 左箭头
            $posX -= 10;
            break;
        case 39: // 右箭头
            $posX += 10;
            break;
        case 27: // ESC 键退出
            echo "ESC 键被按下，退出程序\n";
            $app->quit();
            return 1;
    }

    return 1;
});

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

echo "窗口已显示，使用方向键移动，按 ESC 键退出\n";

// 启动应用循环
$app->run();

echo "应用已退出\n";