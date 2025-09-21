<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Area;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Area Creation Test", 400, 300);

// 创建 Area 组件
echo "创建 Area 组件...\n";
$area = new Area();
echo "Area 组件创建完成\n";

// 创建简单的界面
$box = Box::newVerticalBox();
Box::setPadded($box, true);

$label = Label::create("Area 组件已创建");
Box::append($box, $label, false);

// 设置窗口内容
$window->setContent($box);

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    echo "窗口关闭事件\n";
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

echo "窗口已显示，关闭窗口退出\n";

// 启动应用循环
$app->run();

echo "应用已退出\n";