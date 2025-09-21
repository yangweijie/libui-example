<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Simple Window Test", 400, 300);

// 创建简单的界面
$box = Box::newVerticalBox();
Box::setPadded($box, true);

$label = Label::create("这是一个简单的测试窗口");
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