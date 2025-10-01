<?php

use Kingbes\Libui\Box;
use Kingbes\Libui\Button;
use Kingbes\Libui\Grid;
use Kingbes\Libui\Group;
use Kingbes\Libui\Label;
use Kingbes\Libui\MultilineEntry;
use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\ButtonGroup;
use Yangweijie\Libphp\Components\ComboBox;
use Yangweijie\Libphp\Components\ProgressBar;

require dirname(__DIR__) . "/vendor/autoload.php";

// 创建应用
$app = new Application();

// 创建主窗口
$window = $app->createWindow("oha", 600, 400);

$grid = Grid::create();
$mainBox = Box::newVerticalBox();
Box::setPadded($mainBox, true);

$inputBox = Box::newHorizontalBox();

$inputGroup = Group::create("输入");

$leftBox = Box::newVerticalBox();


$configList = new ComboBox("配置", false);

Box::append($leftBox, $configList->getControl(), true);

$button = Button::create("管理");

Box::append( $configList->getControl(), $button,false);

$testButtons = new ButtonGroup();

$progressBar = new ProgressBar(null, false);
$progressBar->setValue(-1);
$progressBar->hide();


$testButtons->addButton("开始", function ()use($progressBar) {
    echo "添加\n";
    $progressBar->show();
});

$testButtons->addButton("停止", function ()use($progressBar) {
    echo "删除\n";
    $progressBar->hide();
});

Box::append($testButtons->getControl(), $progressBar->getControl(), true);

Box::append($leftBox, $testButtons->getControl(), false);

Group::setChild($inputGroup, $leftBox);

Box::append($inputBox, $inputGroup, false);

$rightBox = Box::newHorizontalBox();

$result = Group::create("结果");

$resultBox = Box::newVerticalBox();

$statusLabel = Label::create('Ready to run test');
Box::append($resultBox, $statusLabel, false);

// Requests per second
$requestsPerSecLabel = Label::create('Requests/sec: --');
Box::append($resultBox, $requestsPerSecLabel, false);

// Total requests
$totalRequestsLabel = Label::create('Total requests: --');
Box::append($resultBox, $totalRequestsLabel, false);

// Success rate
$successRateLabel = Label::create('Success rate: --');
Box::append($resultBox, $successRateLabel, false);

// Performance rating
$performanceLabel = Label::create('Performance: --');
Box::append($resultBox, $performanceLabel, false);

Group::setChild($result, $resultBox);

Group::setMargined($result, true);

Box::append($rightBox, $result, true);

Box::append($inputBox, $rightBox, true);

$bottomBox = Box::newHorizontalBox();

Box::append($mainBox, $inputBox, true);

$outputBox = Group::create("测试输出");

$outputArea = MultilineEntry::createNonWrapping();
MultilineEntry::setReadOnly($outputArea, true);
MultilineEntry::setText($outputArea, 'Test output will appear here...');

Group::setChild($outputBox, $outputArea);

Box::append($bottomBox, $outputBox, true);

Box::append($mainBox, $bottomBox, true);
// 设置窗口内容
$window->setContent($mainBox);

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