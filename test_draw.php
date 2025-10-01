<?php
require "vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Area;
use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("绘图测试", 200, 200, 0);
Window::setMargined($window, true);

// 窗口关闭事件
Window::onClosing($window, function ($window) {
    App::quit();
    return 1;
});

// 创建绘画处理程序
$areaHandler = Area::handler(
    function ($handler, $area, $params) { // 绘画处理程序
        // 创建红色笔刷
        $redBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 0.0, 0.0, 1.0);
        // 创建方块路径
        $drawPath = Draw::createPath(DrawFillMode::Winding);
        // 绘制一个红色的方块
        Draw::pathAddRectangle($drawPath, 50, 50, 100, 100);
        // 结束路径定义
        Draw::pathEnd($drawPath);
        // 填充方块
        Draw::fill($params, $drawPath, $redBrush);
        // 释放路径
        Draw::freePath($drawPath);
    },
    function ($handler, $area, $keyEvent) { // 按键事件
        return 1;
    },
    function ($handler, $area, $mouseEvent) { // 鼠标事件
    }
);

// 创建绘画区域
$area = Area::create($areaHandler);
Window::setChild($window, $area);

// 显示控件
Control::show($window);

// 主循环
App::main();