<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Area;
use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Basic Area Test", 400, 300);

// 创建 Area 组件
$area = new Area();

// 设置绘制回调
$area->onDraw(function ($drawParams) {
    echo "绘制回调被调用\n";

    // 创建简单的画笔
    $brush = Draw::createBrush(DrawBrushType::Solid, 1.0, 0.0, 0.0, 1.0); // 红色

    // 创建简单的路径
    $path = Draw::createPath(DrawFillMode::Winding);
    Draw::pathAddRectangle($path, 50, 50, 100, 100);
    Draw::pathEnd($path);

    // 填充路径
    Draw::fill($drawParams, $path, \FFI::addr($brush));

    echo "绘制完成\n";
});

// 设置键盘事件回调
$area->onKey(function ($keyEvent) use ($area) {
    echo "按键事件: " . $keyEvent->Key . "\n";

    // 按 ESC 键退出
    if ($keyEvent->Key == 27) {
        echo "ESC 键被按下，退出程序\n";
        return 0; // 不处理该事件，让系统处理
    }

    // 触发重绘
    $area->queueRedraw();

    return 1; // 已处理该事件
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

echo "窗口已显示，按 ESC 键退出\n";

// 启动应用循环
$app->run();

echo "应用已退出\n";