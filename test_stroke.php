<?php
require "vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Area;
use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;
use Kingbes\Libui\DrawLineCap;
use Kingbes\Libui\DrawLineJoin;

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("Stroke测试", 200, 200, 0);
Window::setMargined($window, true);

// 窗口关闭事件
Window::onClosing($window, function ($window) {
    App::quit();
    return 1;
});

// 创建绘画处理程序
$areaHandler = Area::handler(
    function ($handler, $area, $params) { // 绘画处理程序
        // 绘制网格线
        $lineBrush = Draw::createBrush(DrawBrushType::Solid, 0.2, 0.2, 0.2, 1.0);
        $linePath = Draw::createPath(DrawFillMode::Winding);

        // 水平线
        Draw::createPathFigure($linePath, 0, 50);
        Draw::pathLineTo($linePath, 200, 50);

        Draw::pathEnd($linePath);
        $strokeParams = Draw::createStrokeParams(
            DrawLineCap::Flat,
            DrawLineJoin::Miter,
            DrawLineJoin::Miter,
            1.0,   // 线宽
            10.0,  // miterLimit
            0,     // numDashes
            0.0    // DashPhase
        );
        Draw::Stroke($params, $linePath, $lineBrush, $strokeParams);
        Draw::freePath($linePath);
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