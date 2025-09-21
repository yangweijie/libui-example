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
$window = $app->createWindow("Area Test", 400, 300);

// 创建 Area 组件
$area = new Area();

// 设置绘制回调
$area->onDraw(function ($drawParams) {
    // 创建画笔
    $brush = Draw::createBrush(DrawBrushType::Solid, 1.0, 0.0, 0.0, 1.0); // 红色

    // 创建路径
    $path = Draw::createPath(DrawFillMode::Winding);
    Draw::pathAddRectangle($path, 50, 50, 100, 100);
    Draw::pathEnd($path);

    // 填充路径
    Draw::fill($drawParams, $path, $brush);

    echo "绘制完成\n";
});

// 设置窗口内容
$window->setContent($area->getControl());

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    echo "窗口关闭\n";
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

// 启动应用循环
$app->run();