<?php

require __DIR__ . "/../vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Box;
use Kingbes\Libui\Button;

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("测试窗口", 300, 200, 0);
Window::setMargined($window, true);

// 窗口关闭事件
Window::onClosing($window, function ($window) {
    App::quit();
    return 1;
});

// 创建垂直容器
$box = Box::newVerticalBox();
Box::setPadded($box, true);
Window::setChild($window, $box);

// 创建标签
$label = Kingbes\Libui\Label::create("Hello, World!");
Box::append($box, $label, false);

// 创建按钮
$button = Button::create("点击我");
Box::append($box, $button, false);

// 按钮点击事件
Button::onClicked($button, function ($btn) use ($label) {
    Kingbes\Libui\Label::setText($label, "按钮被点击了！");
});

// 显示窗口
Control::show($window);

// 主循环
App::main();