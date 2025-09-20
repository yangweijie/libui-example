<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\MessageBox;
use Kingbes\Libui\Box;
use Kingbes\Libui\Button;
use Kingbes\Libui\Control;

// 创建应用
$app = new Application();
$app->init();

// 创建主窗口
$window = $app->createWindow("消息框示例", 400, 100);

// 创建主容器
$mainBox = Box::newVerticalBox();
Box::setPadded($mainBox, true);

// 创建按钮
$showMsgBtn = Button::create("显示消息框");
Button::onClicked($showMsgBtn, function ($btn) use ($window) {
    $window->showMessage("提示", "这是一个消息框示例！");
});

$showErrorBtn = Button::create("显示错误框");
Button::onClicked($showErrorBtn, function ($btn) use ($window) {
    $window->showError("错误", "这是一个错误框示例！");
});

$showCustomMsgBtn = Button::create("显示自定义消息框");
Button::onClicked($showCustomMsgBtn, function ($btn) use ($window) {
    // 创建自定义消息框
    $msgBox = new MessageBox("自定义消息框", "这是一个自定义消息框示例！", 300, 90);
    $msgBox->addButton("确定", function ($btn) {
        echo "确定按钮被点击\n";
    },true)->addButton("取消", function ($btn) {
        echo "取消按钮被点击\n";
        return false;
    }, true);
    $msgBox->show();
});

// 将按钮添加到容器
Box::append($mainBox, $showMsgBtn, false);
Box::append($mainBox, $showErrorBtn, false);
Box::append($mainBox, $showCustomMsgBtn, false);

// 设置窗口内容
$window->setContent($mainBox);

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    echo "窗口关闭\n";
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

// 运行应用
$app->run();