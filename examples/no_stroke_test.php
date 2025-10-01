<?php

require __DIR__ . "/../vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Area;
use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("无Stroke绘图测试", 400, 300, 0);
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
$label = Label::create("无Stroke绘图测试 - 应该不会崩溃");
Box::append($box, $label, false);

// 创建绘图区域处理程序
$areaHandler = Area::handler(
    function ($handler, $area, $params) { // 绘制回调
        // 使用fill方法绘制矩形网格，避免使用Stroke方法
        $brush = Draw::createBrush(DrawBrushType::Solid, 0.3, 0.3, 0.3, 1.0);
        
        // 绘制垂直线
        for ($i = 0; $i <= 10; $i++) {
            $path = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($path, $i * 30, 0, 1, 200);
            Draw::pathEnd($path);
            Draw::fill($params, $path, $brush);
        }
        
        // 绘制水平线
        for ($i = 0; $i <= 10; $i++) {
            $path = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($path, 0, $i * 20, 300, 1);
            Draw::pathEnd($path);
            Draw::fill($params, $path, $brush);
        }
        
        // 绘制彩色方块
        $colors = [
            [1.0, 0.0, 0.0, 1.0], // 红色
            [0.0, 1.0, 0.0, 1.0], // 绿色
            [0.0, 0.0, 1.0, 1.0], // 蓝色
            [1.0, 1.0, 0.0, 1.0], // 黄色
        ];
        
        for ($i = 0; $i < 4; $i++) {
            $colorBrush = Draw::createBrush(DrawBrushType::Solid, $colors[$i][0], $colors[$i][1], $colors[$i][2], $colors[$i][3]);
            $path = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($path, 50 + $i * 40, 50, 30, 30);
            Draw::pathEnd($path);
            Draw::fill($params, $path, $colorBrush);
            
            // 绘制边框（使用细长矩形模拟）
            $borderBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 1.0, 1.0, 0.5);
            
            // 上边框
            $topBorder = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($topBorder, 50 + $i * 40, 50, 30, 1);
            Draw::pathEnd($topBorder);
            Draw::fill($params, $topBorder, $borderBrush);
            
            // 下边框
            $bottomBorder = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($bottomBorder, 50 + $i * 40, 50 + 29, 30, 1);
            Draw::pathEnd($bottomBorder);
            Draw::fill($params, $bottomBorder, $borderBrush);
            
            // 左边框
            $leftBorder = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($leftBorder, 50 + $i * 40, 50, 1, 30);
            Draw::pathEnd($leftBorder);
            Draw::fill($params, $leftBorder, $borderBrush);
            
            // 右边框
            $rightBorder = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($rightBorder, 50 + $i * 40 + 29, 50, 1, 30);
            Draw::pathEnd($rightBorder);
            Draw::fill($params, $rightBorder, $borderBrush);
        }
    }
);

// 创建绘图区域
$drawArea = Area::create($areaHandler);
Box::append($box, $drawArea, true);

// 显示窗口
Control::show($window);

// 主循环
App::main();