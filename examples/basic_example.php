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

// 创建应用
$app = new Application();

// 创建主窗口
$window = $app->createWindow("LibPHP 组件示例", 600, 400);

// 创建主容器
$mainBox = Box::newVerticalBox();
Box::setPadded($mainBox, true);

// 创建标签页面板
$tabPanel = new TabPanel();

// 第一个标签页：按钮组示例
$buttonGroupBox = Box::newVerticalBox();
Box::setPadded($buttonGroupBox, true);

$label1 = Label::create("按钮组示例：");
Box::append($buttonGroupBox, $label1, false);

$buttonGroup = new ButtonGroup(false, true);
$buttonGroup->addButton("按钮1", function ($btn, $index) use ($window) {
    $window->showMessage("提示", "按钮1被点击了");
})->addButton("按钮2", function ($btn, $index) use ($window) {
    $window->showMessage("提示", "按钮2被点击了");
})->addButton("按钮3", function ($btn, $index) use ($window) {
    $window->showMessage("提示", "按钮3被点击了");
});

Box::append($buttonGroupBox, $buttonGroup->getControl(), false);
$tabPanel->addPage("按钮组", $buttonGroupBox);

// 第二个标签页：表单示例
$formBox = Box::newVerticalBox();
Box::setPadded($formBox, true);

$label2 = Label::create("表单示例：");
Box::append($formBox, $label2, false);

$form = new Form(true);
$form->addTextField("用户名:", "", false, function ($entry, $index) use ($form) {
    echo "用户名改变: " . $form->getFieldValue($index) . "\n";
})->addPasswordField("密码:", function ($entry, $index) use ($form) {
    echo "密码改变\n";
})->addTextField("邮箱:", "", false, function ($entry, $index) use ($form) {
    echo "邮箱改变: " . $form->getFieldValue($index) . "\n";
});

Box::append($formBox, $form->getControl(), false);
$tabPanel->addPage("表单", $formBox);

// 第三个标签页：复选框组示例
$checkboxBox = Box::newVerticalBox();
Box::setPadded($checkboxBox, true);

$label3 = Label::create("复选框组示例：");
Box::append($checkboxBox, $label3, false);

$checkboxGroup = new CheckboxGroup(true, true);
$checkboxGroup->addCheckbox("选项1", false, function ($cb, $isChecked, $index) use ($window) {
    $window->showMessage("提示", "选项1 " . ($isChecked ? "选中" : "取消选中"));
})->addCheckbox("选项2", true, function ($cb, $isChecked, $index) use ($window) {
    $window->showMessage("提示", "选项2 " . ($isChecked ? "选中" : "取消选中"));
})->addCheckbox("选项3", false, function ($cb, $isChecked, $index) use ($window) {
    $window->showMessage("提示", "选项3 " . ($isChecked ? "选中" : "取消选中"));
});

Box::append($checkboxBox, $checkboxGroup->getControl(), false);
$tabPanel->addPage("复选框", $checkboxBox);

// 第四个标签页：滑块示例
$sliderBox = Box::newVerticalBox();
Box::setPadded($sliderBox, true);

$label4 = Label::create("滑块示例：");
Box::append($sliderBox, $label4, false);

$sliderControl = new SliderControl("音量:", 0, 100, 50, true, false);
$sliderControl->onChange(function ($value) use ($window) {
    echo "滑块值改变: $value\n";
});

Box::append($sliderBox, $sliderControl->getControl(), false);
$tabPanel->addPage("滑块", $sliderBox);

// 将标签页面板添加到主容器
Box::append($mainBox, $tabPanel->getControl(), true);

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