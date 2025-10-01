<?php
require "vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Area;
use Kingbes\Libui\Draw;
use Kingbes\Libui\Attribute;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;
use Kingbes\Libui\TextAlign;
use Kingbes\Libui\TextWeight;
use Kingbes\Libui\TextItalic;
use Kingbes\Libui\TextStretch;

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("文字测试", 400, 300, 0);
Window::setMargined($window, true);

// 窗口关闭事件
Window::onClosing($window, function ($window) {
    App::quit();
    return 1;
});

// 创建绘画处理程序
$areaHandler = Area::handler(
    function ($handler, $area, $params) { // 绘画处理程序
        // 创建属性字符串
        $attrStr = Attribute::createString("Hello World!");
        
        // 创建字体描述符
        $fontDesc = Draw::createFontDesc("Arial", 24.0, TextWeight::Normal, TextItalic::Normal, TextStretch::Normal);
        
        // 创建文字布局参数
        $textLayoutParams = Draw::createTextLayoutParams(
            $attrStr,
            $fontDesc,
            200,
            TextAlign::Left
        );
        
        // 创建文字布局
        $textLayout = Draw::createTextLayout($textLayoutParams);
        
        // 绘制文字
        Draw::text($params, $textLayout, 50, 50);
        
        // 释放资源
        Draw::freeTextLayout($textLayout);
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