<?php

require __DIR__ . "/../vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Area;

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("按键测试", 300, 200, 0);
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

// 创建标签显示按键信息
$keyLabel = Label::create("请按键测试...");
Box::append($box, $keyLabel, false);

// 创建区域处理程序
$areaHandler = Area::handler(
    function ($handler, $area, $params) { // 绘制回调
        // 简单的绘制
    },
    function ($handler, $area, $keyEvent) use ($keyLabel) { // 按键回调
        // 显示按键信息
        $keyInfo = "按键: " . $keyEvent->Key . " (Up: " . $keyEvent->Up . ")";
        Label::setText($keyLabel, $keyInfo);
        return 1;
    }
);

// 创建区域
$testArea = Area::create($areaHandler);
Box::append($box, $testArea, true);

// 显示窗口
Control::show($window);

// 主循环
App::main();