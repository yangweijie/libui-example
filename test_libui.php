<?php
require "vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("测试窗口", 200, 100, 0);

// 窗口关闭事件
Window::onClosing($window, function ($window) {
    App::quit();
    return 1;
});

// 显示控件
Control::show($window);

// 主循环
App::main();