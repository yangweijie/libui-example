<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\WindowWrapper;
use Yangweijie\Libphp\Components\TabPanel;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Spinbox;
use Kingbes\Libui\Area;
use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;
use Kingbes\Libui\DrawLineCap;
use Kingbes\Libui\DrawLineJoin;

// 创建应用
$app = new Application();

// 创建主窗口
$window = $app->createWindow("Area-Based Custom Controls", 385, 385);

// 创建标签页面板
$tabPanel = new TabPanel();

// === 创建文本标签页 ===
$textLabelBox = Box::newVerticalBox();
Box::setPadded($textLabelBox, true);

// 添加表单标题
$formTitle1 = Label::create("Text Label Form:");
Box::append($textLabelBox, $formTitle1, false);

// 宽度控制
$widthBox1 = Box::newHorizontalBox();
Box::setPadded($widthBox1, true);
Box::append($widthBox1, Label::create("Width"), false);
$widthSpinbox1 = Spinbox::create(1, 1000);
Spinbox::setValue($widthSpinbox1, 335);
Box::append($widthBox1, $widthSpinbox1, true);
Box::append($textLabelBox, $widthBox1, false);

// 高度控制
$heightBox1 = Box::newHorizontalBox();
Box::setPadded($heightBox1, true);
Box::append($heightBox1, Label::create("Height"), false);
$heightSpinbox1 = Spinbox::create(1, 1000);
Spinbox::setValue($heightSpinbox1, 50);
Box::append($heightBox1, $heightSpinbox1, true);
Box::append($textLabelBox, $heightBox1, false);

// 创建文本标签区域处理程序
$textLabelAreaHandler = Area::handler(
    function ($handler, $area, $drawParams) use ($widthSpinbox1, $heightSpinbox1) {
        // 获取区域尺寸
        $areaWidth = $drawParams[0]->AreaWidth;
        $areaHeight = $drawParams[0]->AreaHeight;
        
        // 获取输入框的值
        $labelWidth = Spinbox::value($widthSpinbox1);
        $labelHeight = Spinbox::value($heightSpinbox1);
        
        // 填充背景为白色
        $whiteBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 1.0, 1.0, 1.0);
        $bgPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($bgPath, 0, 0, $areaWidth, $areaHeight);
        Draw::pathEnd($bgPath);
        Draw::fill($drawParams, $bgPath, $whiteBrush);
        Draw::freePath($bgPath);
        
        // 绘制黄色背景矩形
        $yellowBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 1.0, 0.0, 1.0); // 黄色
        $labelPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($labelPath, 1, 1, $labelWidth, $labelHeight);
        Draw::pathEnd($labelPath);
        Draw::fill($drawParams, $labelPath, $yellowBrush);
        Draw::freePath($labelPath);
        
        // 绘制绿色边框
        $greenBrush = Draw::createBrush(DrawBrushType::Solid, 0.0, 1.0, 0.0, 1.0); // 绿色
        $strokeParams = Draw::createStrokeParams(
            DrawLineCap::Flat,
            DrawLineJoin::Miter,
            DrawLineJoin::Miter,
            1.0
        );
        $borderPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($borderPath, 1, 1, $labelWidth, $labelHeight);
        Draw::pathEnd($borderPath);
        Draw::Stroke($drawParams, $borderPath, $greenBrush, $strokeParams);
        Draw::freePath($borderPath);
    }
);

// 创建文本标签区域
$textLabelArea = Area::create($textLabelAreaHandler);

// 为输入框添加事件处理程序，当值改变时重绘区域
Spinbox::onChanged($widthSpinbox1, function($sb) use ($textLabelArea) {
    Area::queueRedraw($textLabelArea);
});

Spinbox::onChanged($heightSpinbox1, function($sb) use ($textLabelArea) {
    Area::queueRedraw($textLabelArea);
});

Box::append($textLabelBox, $textLabelArea, true);

// 添加文本标签页到标签页面板
$tabPanel->addPage("Text Label", $textLabelBox, true);

// === 创建按钮标签页 ===
$pushButtonBox = Box::newVerticalBox();
Box::setPadded($pushButtonBox, true);

// 添加表单标题
$formTitle2 = Label::create("Push Button Form:");
Box::append($pushButtonBox, $formTitle2, false);

// 宽度控制
$widthBox2 = Box::newHorizontalBox();
Box::setPadded($widthBox2, true);
Box::append($widthBox2, Label::create("Width"), false);
$widthSpinbox2 = Spinbox::create(1, 1000);
Spinbox::setValue($widthSpinbox2, 150);
Box::append($widthBox2, $widthSpinbox2, true);
Box::append($pushButtonBox, $widthBox2, false);

// 高度控制
$heightBox2 = Box::newHorizontalBox();
Box::setPadded($heightBox2, true);
Box::append($heightBox2, Label::create("Height"), false);
$heightSpinbox2 = Spinbox::create(1, 1000);
Spinbox::setValue($heightSpinbox2, 50);
Box::append($heightBox2, $heightSpinbox2, true);
Box::append($pushButtonBox, $heightBox2, false);

// 创建按钮区域处理程序
$pushButtonHandler = Area::handler(
    function ($handler, $area, $drawParams) use ($widthSpinbox2, $heightSpinbox2) {
        // 获取区域尺寸
        $areaWidth = $drawParams[0]->AreaWidth;
        $areaHeight = $drawParams[0]->AreaHeight;
        
        // 获取输入框的值
        $buttonWidth = Spinbox::value($widthSpinbox2);
        $buttonHeight = Spinbox::value($heightSpinbox2);
        
        // 填充背景为白色
        $whiteBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 1.0, 1.0, 1.0);
        $bgPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($bgPath, 0, 0, $areaWidth, $areaHeight);
        Draw::pathEnd($bgPath);
        Draw::fill($drawParams, $bgPath, $whiteBrush);
        Draw::freePath($bgPath);
        
        // 绘制灰色背景矩形
        $grayBrush = Draw::createBrush(DrawBrushType::Solid, 0.8, 0.8, 0.8, 1.0); // 灰色
        $buttonPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($buttonPath, 1, 1, $buttonWidth, $buttonHeight);
        Draw::pathEnd($buttonPath);
        Draw::fill($drawParams, $buttonPath, $grayBrush);
        Draw::freePath($buttonPath);
        
        // 绘制黑色边框
        $blackBrush = Draw::createBrush(DrawBrushType::Solid, 0.0, 0.0, 0.0, 1.0); // 黑色
        $strokeParams = Draw::createStrokeParams(
            DrawLineCap::Flat,
            DrawLineJoin::Miter,
            DrawLineJoin::Miter,
            1.0
        );
        $borderPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($borderPath, 1, 1, $buttonWidth, $buttonHeight);
        Draw::pathEnd($borderPath);
        Draw::Stroke($drawParams, $borderPath, $blackBrush, $strokeParams);
        Draw::freePath($borderPath);
    },
    function ($handler, $area, $keyEvent) {
        // 按键事件处理
    },
    function ($handler, $area, $mouseEvent) use ($widthSpinbox2, $heightSpinbox2) {
        // 鼠标事件处理
        // 注意：$mouseEvent 已经是结构体指针，不需要再用数组索引访问
        $x = $mouseEvent->X;
        $y = $mouseEvent->Y;
        
        // 获取输入框的值
        $buttonWidth = Spinbox::value($widthSpinbox2);
        $buttonHeight = Spinbox::value($heightSpinbox2);
        
        // 检查鼠标点击是否在按钮区域内
        // 按钮区域: x=1-(1+buttonWidth), y=1-(1+buttonHeight)
        if ($x >= 1 && $x <= (1 + $buttonWidth) && $y >= 1 && $y <= (1 + $buttonHeight)) {
            if ($mouseEvent->Down == 1) {
                echo "按钮被点击了！\n";
            }
        }
    }
);

// 创建按钮区域
$pushButtonArea = Area::create($pushButtonHandler);

// 为输入框添加事件处理程序，当值改变时重绘区域
Spinbox::onChanged($widthSpinbox2, function($sb) use ($pushButtonArea) {
    Area::queueRedraw($pushButtonArea);
});

Spinbox::onChanged($heightSpinbox2, function($sb) use ($pushButtonArea) {
    Area::queueRedraw($pushButtonArea);
});

Box::append($pushButtonBox, $pushButtonArea, true);

// 添加按钮标签页到标签页面板
$tabPanel->addPage("Push Button", $pushButtonBox, true);

// 设置窗口内容
$window->setContent($tabPanel->getControl());

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    echo "窗口关闭\n";
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

// 运行应用
$app->run();