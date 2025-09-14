<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\WindowWrapper;
use Yangweijie\Libphp\Components\TabPanel;
use Yangweijie\Libphp\Components\ComboBox;
use Yangweijie\Libphp\Components\SpinBox;
use Yangweijie\Libphp\Components\ProgressBar;
use Kingbes\Libui\DateTimePicker;
use Kingbes\Libui\DateTime;
use Yangweijie\Libphp\Components\EditableComboBox;
use Yangweijie\Libphp\Components\MultilineEntry;
use Yangweijie\Libphp\Components\Radio;
use Kingbes\Libui\Box;
use Kingbes\Libui\Button;
use Kingbes\Libui\Label;
use Kingbes\Libui\Grid;
use Kingbes\Libui\Group;
use Kingbes\Libui\Separator;
use Kingbes\Libui\Align;
use Kingbes\Libui\Table;
use Kingbes\Libui\TableValueType;

// 创建应用
$app = new Application();

$datetime = new DateTime(
    50,
    30,
    10,
    10,
    10,
    2023
);

// 创建主窗口（启用菜单栏）
$window = $app->createWindow("组合示例 - 所有组件展示", 700, 600, true);


// === 扩展组件示例 (标签页1) ===
$extendedBox = Box::newVerticalBox();
Box::setPadded($extendedBox, true);

// 创建下拉列表框示例
$comboLabel = Label::create("下拉列表框示例：");
Box::append($extendedBox, $comboLabel, false);

$comboBox = new ComboBox("选择城市:", true);
$comboBox->addItems(["北京", "上海", "广州", "深圳", "杭州", "南京", "成都"])
    ->setSelectedIndex(0);

// 为下拉列表框设置事件处理
$comboBox->onChange(function ($combo, $index, $text) use ($window) {
    $window->showMessage("提示", "选中了: " . $text . " (索引: " . $index . ")");
});

Box::append($extendedBox, $comboBox->getControl(), false);

// 创建微调框示例
$spinLabel = Label::create("微调框示例：");
Box::append($extendedBox, $spinLabel, false);

$spinBox = new SpinBox("年龄:", 0, 120, 25, true);

// 为微调框设置事件处理
$spinBox->onChange(function ($spin, $value) use ($window) {
    echo "年龄改变为: " . $value . "\n";
});

Box::append($extendedBox, $spinBox->getControl(), false);

// 创建进度条示例
$progressLabel = Label::create("进度条示例：");
Box::append($extendedBox, $progressLabel, false);

$progressBar = new ProgressBar("进度:", true, true);

// 添加控制按钮
$buttonBox = Box::newHorizontalBox();
Box::setPadded($buttonBox, true);

$startButton = Button::create("开始");
$resetButton = Button::create("重置");

Box::append($buttonBox, $startButton, true);
Box::append($buttonBox, $resetButton, true);

// 进度条控制逻辑
$currentProgress = 0;

Button::onClicked($startButton, function () use (&$currentProgress, $progressBar) {
    // 模拟进度更新
    if ($currentProgress < 100) {
        $currentProgress += 10;
        $progressBar->setValue($currentProgress);
    }
});

Button::onClicked($resetButton, function () use (&$currentProgress, $progressBar) {
    $currentProgress = 0;
    $progressBar->reset();
});

Box::append($extendedBox, $progressBar->getControl(), false);
Box::append($extendedBox, $buttonBox, false);

// === 更多组件示例 (标签页2) ===
$moreBox = Box::newVerticalBox();
Box::setPadded($moreBox, true);

// 创建日期时间选择器示例
$dateTimeLabel = Label::create("日期时间选择器示例：");
Box::append($moreBox, $dateTimeLabel, false);

// 创建日期时间选择器
$dateTimePicker = DateTimePicker::createDataTime();
// 设置时间为2023年
DateTimePicker::setTime($dateTimePicker, new DateTime(
    50,
    30,
    10,
    10,
    10,
    2023
));
// 创建日期选择器
$datePicker = DateTimePicker::createDate();
// 创建时间选择器
$timePicker = DateTimePicker::createTime();

DateTimePicker::onChanged($dateTimePicker, function ($dateTimePicker)use(&$datetime) {
    echo "时间日期时间选择器事件";
    // 显示选中的事件
    $datetime = DateTimePicker::time($dateTimePicker);
    var_dump(DateTimePicker::time($dateTimePicker));
});

// 日期时间选择器
// $dateTimePicker->onChange(function ($picker) use ($window, $dateTimePicker) {
//     // 获取时间并显示
//     try {
//         $time = $dateTimePicker->getTime();
//         $timeStr = sprintf("%04d-%02d-%02d %02d:%02d:%02d", 
//             $time['year'] + 1900, $time['month'] + 1, $time['day'],
//             $time['hour'], $time['minute'], $time['second']);
//         $window->showMessage("提示", "选择了日期时间: " . $timeStr);
//     } catch (Exception $e) {
//         $window->showMessage("错误", "获取日期时间时出错: " . $e->getMessage());
//     }
// });

Box::append($moreBox, $dateTimePicker, false);

// 日期选择器
//日期选择器事件
DateTimePicker::onChanged($datePicker, function ($datePicker) {
    echo "日期选择器事件";
    // 显示选中的事件
    var_dump(DateTimePicker::time($datePicker));
});

Box::append($moreBox, $datePicker, false);

// 时间选择器
DateTimePicker::onChanged($timePicker, function ($timePicker) {
    echo "时间选择器事件";
    // 显示选中的事件
    var_dump(DateTimePicker::time($timePicker));
});
// $timePicker->onChange(function ($picker) use ($window) {
//     $window->showMessage("提示", "选择了时间");
// });

Box::append($moreBox, $timePicker, false);

// 创建可编辑下拉列表框示例
$editableComboLabel = Label::create("可编辑下拉列表框示例：");
Box::append($moreBox, $editableComboLabel, false);

$editableComboBox = new EditableComboBox("城市:", true);
$editableComboBox->addItems(["北京", "上海", "广州", "深圳", "杭州"])
    ->setText("请输入或选择城市");

$editableComboBox->onChange(function ($combo, $text) use ($window) {
    echo "可编辑下拉框文本改变为: " . $text . "\n";
});

Box::append($moreBox, $editableComboBox->getControl(), false);

// 创建多行文本输入框示例
$multilineLabel = Label::create("多行文本输入框示例：");
Box::append($moreBox, $multilineLabel, false);

$multilineEntry = new MultilineEntry("描述:", true, true);
$multilineEntry->setText("请输入描述信息...\n支持多行输入");

$multilineEntry->onChange(function ($entry, $text) use ($window) {
    echo "多行文本改变\n";
});

Box::append($moreBox, $multilineEntry->getControl(), false);

// 创建单选按钮组示例
$radioLabel = Label::create("单选按钮组示例：");
Box::append($moreBox, $radioLabel, false);

$radioGroup = new Radio("选择性别:", true);
$radioGroup->addItems(["男", "女", "其他"])
    ->setSelectedIndex(0);

$radioGroup->onChange(function ($radio, $index, $text) use ($window) {
    $window->showMessage("提示", "选择了: " . $text . " (索引: " . $index . ")");
});

Box::append($moreBox, $radioGroup->getControl(), false);

// 添加控制按钮
$moreButtonBox = Box::newHorizontalBox();
Box::setPadded($moreButtonBox, true);

$showButton = Button::create("显示信息");
$resetMoreButton = Button::create("重置");

Box::append($moreButtonBox, $showButton, true);
Box::append($moreButtonBox, $resetMoreButton, true);

Button::onClicked($showButton, function () use ($editableComboBox, $multilineEntry, $radioGroup, $window, $dateTimePicker, $datetime) {
    $editableText = $editableComboBox->getText();
    $multilineText = $multilineEntry->getText();
    $radioIndex = $radioGroup->getSelectedIndex();
    $radioText = $radioGroup->getSelectedText();
    
    // 获取日期时间信息
    $timeStr = "";
    try {
        $time = $datetime;
        $timeStr = sprintf("%04d-%02d-%02d %02d:%02d:%02d", 
            $time->year, $time->mon, $time->mday,
            $time->hour, $time->min, $time->sec);
    } catch (Exception $e) {
        $timeStr = "获取日期时间时出错: " . $e->getMessage();
    }
    
    $info = "城市: " . $editableText . "\n" .
            "描述: " . $multilineText . "\n" .
            "性别: " . $radioText . " (索引: " . $radioIndex . ")\n" .
            "日期时间: " . $timeStr;
    
    $window->showMessage("信息", $info);
});

Button::onClicked($resetMoreButton, function () use ($editableComboBox, $multilineEntry, $radioGroup) {
    $editableComboBox->setText("请输入或选择城市");
    $multilineEntry->setText("请输入描述信息...\n支持多行输入");
    $radioGroup->setSelectedIndex(0);
});

Box::append($moreBox, $moreButtonBox, false);

// === 新组件示例 (标签页3) ===
$newComponentsBox = Box::newVerticalBox();
Box::setPadded($newComponentsBox, true);

// 创建组面板示例
$groupLabel = Label::create("组面板示例：");
Box::append($newComponentsBox, $groupLabel, false);

// 创建一个组
$group = Group::create("用户信息");
Group::setMargined($group, true);

// 在组内创建一个垂直盒子布局
$groupBox = Box::newVerticalBox();
Box::setPadded($groupBox, true);

// 添加一些控件到组内
$nameLabel = Label::create("姓名: 张三");
$ageLabel = Label::create("年龄: 25岁");
$emailLabel = Label::create("邮箱: zhangsan@example.com");

Box::append($groupBox, $nameLabel, false);
Box::append($groupBox, $ageLabel, false);
Box::append($groupBox, $emailLabel, false);

// 将盒子设置为组的子控件
Group::setChild($group, $groupBox);

Box::append($newComponentsBox, $group, false);

// 添加分隔符
$separator = Separator::createHorizontal();
Box::append($newComponentsBox, $separator, false);

// 创建网格布局示例
$gridLabel = Label::create("网格布局示例：");
Box::append($newComponentsBox, $gridLabel, false);

// 创建网格布局
// $grid = Grid::create();
// Grid::setPadded($grid, true);

// // 创建一些控件并添加到网格中
// $label1 = Label::create("标签1:");
// $label2 = Label::create("标签2:");
// $label3 = Label::create("标签3:");
// $button1 = Button::create("按钮1");
// $button2 = Button::create("按钮2");
// $button3 = Button::create("按钮3");

// // 将控件添加到网格中
// Grid::append($grid, $label1, 0, 0, 1, 1, 0, 0, 0, Align::Center);
// Grid::append($grid, $button1, 1, 0, 1, 1, 0, 0, 0, Align::Center);
// Grid::append($grid, $label2, 0, 1, 1, 1, 0, 0, 0, Align::Center);
// Grid::append($grid, $button2, 1, 1, 1, 1, 0, 0, 0, Align::Center);
// Grid::append($grid, $label3, 0, 2, 1, 1, 0, 0, 0, Align::Center);
// Grid::append($grid, $button3, 1, 2, 1, 1, 0, 0, 0, Align::Center);

// Box::append($newComponentsBox, $grid, false);


// 将示例添加到标签页中
$tabPanel = new TabPanel();

// 将示例添加到标签页中
$tabPanel->addPage("扩展组件示例", $extendedBox, true);
$tabPanel->addPage("更多组件示例", $moreBox, true);
$tabPanel->addPage("新组件示例", $newComponentsBox, true);

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