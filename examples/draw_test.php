<?php

require __DIR__ . "/../vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Button;
use Kingbes\Libui\Area;
use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("绘图测试", 400, 300, 0);
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
$label = Label::create("绘图区域测试");
Box::append($box, $label, false);

// 创建绘图区域处理程序
$areaHandler = Area::handler(
    function ($handler, $area, $params) { // 绘制回调
        // 绘制一个简单的矩形
        $brush = Draw::createBrush(DrawBrushType::Solid, 1.0, 0.0, 0.0, 1.0); // 红色
        $path = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($path, 10, 10, 100, 100);
        Draw::pathEnd($path);
        Draw::fill($params, $path, $brush);
    }
);

// 创建绘图区域
$drawArea = Area::create($areaHandler);
Box::append($box, $drawArea, true);

// 显示窗口
Control::show($window);

// 主循环
App::main();