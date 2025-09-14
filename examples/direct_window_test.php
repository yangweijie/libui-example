<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Menu;
use Kingbes\Libui\Window;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\App;
use Kingbes\Libui\Control;

// 创建应用
$app = new Application();
$app->init(); // 显式初始化应用

// 先创建菜单
$fileMenu = new Menu("文件");
$fileMenu->addItem("新建", function ($item) {
    echo "新建文件\n";
})->addItem("打开", function ($item) {
    echo "打开文件\n";
})->addSeparator()->addQuitItem();

$editMenu = new Menu("编辑");
$editMenu->addItem("撤销", function ($item) {
    echo "撤销操作\n";
})->addItem("重做", function ($item) {
    echo "重做操作\n";
});

$viewMenu = new Menu("查看");
$viewMenu->addCheckItem("状态栏", true, function ($item, $isChecked) {
    echo "状态栏 " . ($isChecked ? "显示" : "隐藏") . "\n";
});

$helpMenu = new Menu("帮助");
$helpMenu->addAboutItem(function ($item) {
    echo "关于 LibPHP 示例应用\n";
});

// 然后创建主窗口（启用菜单栏）
$window = Window::create("直接窗口测试", 400, 300, 1); // 第四个参数设为1启用菜单栏

// 创建主容器
$mainBox = Box::newVerticalBox();
Box::setPadded($mainBox, true);

// 添加标签
$label = Label::create("直接窗口测试 - 请查看窗口顶部的菜单栏");
Box::append($mainBox, $label, false);

// 设置窗口内容
Window::setChild($window, $mainBox);

// 设置窗口关闭事件
Window::onClosing($window, function ($w) use ($app) {
    echo "窗口关闭\n";
    $app->quit();
    return 1; // 返回1表示允许关闭
});

// 显示窗口
Control::show($window);

// 运行应用
App::main();