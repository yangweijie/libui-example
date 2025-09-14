<?php
require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\ButtonGroup;
use Yangweijie\Libphp\Components\Form;
use Yangweijie\Libphp\Components\CheckboxGroup;
use Yangweijie\Libphp\Components\SliderControl;
use Yangweijie\Libphp\Components\TabPanel;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\DateTimePicker;
use Kingbes\Libui\DateTime;

// 创建应用
$app = new Application();

// 创建主窗口
$window = $app->createWindow("LibPHP 组件示例", 600, 400);

// 创建主容器
$mainBox = Box::newVerticalBox();
Box::setPadded($mainBox, true);

// 创建日期时间选择器
$dateTimePicker = DateTimePicker::createDataTime();
// 创建日期选择器
$datePicker = DateTimePicker::createDate();
// 创建时间选择器
$timePicker = DateTimePicker::createTime();

// 设置时间为2023年
DateTimePicker::setTime($dateTimePicker, new DateTime(
    50,
    30,
    10,
    10,
    10,
    2023
));

// 追加按钮到容器
Box::append($mainBox, $dateTimePicker, false);
Box::append($mainBox, $datePicker, false);
Box::append($mainBox, $timePicker, false);

//时间日期时间选择器事件
DateTimePicker::onChanged($dateTimePicker, function ($dateTimePicker) {
    echo "时间日期时间选择器事件";
    // 显示选中的事件
    var_dump(DateTimePicker::time($dateTimePicker));
});
//日期选择器事件
DateTimePicker::onChanged($datePicker, function ($datePicker) {
    echo "日期选择器事件";
    // 显示选中的事件
    var_dump(DateTimePicker::time($datePicker));
});
//时间选择器事件
DateTimePicker::onChanged($timePicker, function ($timePicker) {
    echo "时间选择器事件";
    // 显示选中的事件
    var_dump(DateTimePicker::time($timePicker));
});

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