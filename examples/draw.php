<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Draw;
use Yangweijie\Libphp\Components\Area;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;

// 初始化应用
App::init();
// 创建窗口
$window = Window::create("窗口", 640, 480, 0);
// 窗口设置边框
Window::setMargined($window, true);
// 窗口关闭事件
Window::onClosing($window, function ($window) {
    echo "窗口关闭";
    // 退出应用
    App::quit();
    // 返回1：奏效,返回0：不奏效
    return 1;
});

// 创建绘画区域
$areaWrapper = new Area();
$areaWrapper->onDraw(function ($drawParams) {
    // 创建红色笔刷
    $redBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 0.0, 0.0, 1.0);
    // 创建方块路径
    $drawPath = Draw::createPath(DrawFillMode::Winding);
    Draw::pathAddRectangle($drawPath, 100, 100, 200, 200);
    Draw::pathEnd($drawPath);

    // 绘制文本 (disabled: pending str2CData implementation)
    $fontDesc = Draw::createFontDescriptor();
    Draw::setFontFamily($fontDesc, 'Segoe UI Emoji');
    Draw::setFontSize($fontDesc, 24);
    // $textParams = Draw::createTextLayoutParams();
    // Draw::setTextLayoutParamsDefaultFont($textParams, $fontDesc);
    // Draw::setTextLayoutParamsWidth($textParams, 400);
    // Draw::setTextLayoutParamsString($textParams, '✅ File written successfully! (5');
    // $textLayout = Draw::createTextLayout($textParams);
    // Draw::text($drawParams, $textLayout, 150, 350);
    // Text rendering disabled due to layout stub
    /*
    $fontDesc = Draw::createFontDescriptor();
    Draw::setFontFamily($fontDesc, 'Segoe UI Emoji');
    Draw::setFontSize($fontDesc, 24);
    $textParams = Draw::createTextLayoutParams();
    Draw::setTextLayoutParamsDefaultFont($textParams, $fontDesc);
    Draw::setTextLayoutParamsWidth($textParams, 400);
    Draw::setTextLayoutParamsString($textParams, '✅ File written successfully! (5');
    $textLayout = Draw::createTextLayout($textParams);
    Draw::text($drawParams, $textLayout, 150, 350);
    */
    Draw::setFontFamily($fontDesc, 'Segoe UI Emoji');
    Draw::setFontSize($fontDesc, 24);
    $textParams = Draw::createTextLayoutParams();
    Draw::setTextLayoutParamsDefaultFont($textParams, $fontDesc);
    Draw::setTextLayoutParamsWidth($textParams, 400);
    Draw::setTextLayoutParamsString($textParams, '✅ File written successfully! (5');
    $textLayout = Draw::createTextLayout($textParams);
    Draw::text($drawParams, $textLayout, 150, 350);

    // 填充方块
    Draw::fill($drawParams, $drawPath, $redBrush);
});

Window::setChild($window, $areaWrapper->getControl());

// 显示控件
Control::show($window);
// 主循环
App::main();
