# LibPHP

为 [kingbes/libui](https://github.com/KingBes/php-libui) 封装的常用 GUI 组件库，方便开发 PHP GUI 应用程序。

## 安装

```bash
composer require yangweijie/libphp
```

## 功能特性

本库提供了以下常用 GUI 组件的封装：

1. **Application** - 应用程序管理器
2. **WindowWrapper** - 窗口包装器
3. **ButtonGroup** - 按钮组组件
4. **Form** - 表单组件
5. **CheckboxGroup** - 复选框组组件
6. **SliderControl** - 滑块控制组件
7. **TabPanel** - 标签页面板组件
8. **MessageBox** - 消息框组件
9. **ComboBox** - 下拉列表框组件
10. **SpinBox** - 微调框组件
11. **ProgressBar** - 进度条组件
12. **DateTimePicker** - 日期时间选择器组件
13. **EditableComboBox** - 可编辑下拉列表框组件
14. **MultilineEntry** - 多行文本输入框组件
15. **Radio** - 单选按钮组组件

## 使用示例

### 基本示例

```php
<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\ButtonGroup;
use Kingbes\Libui\Box;

// 创建应用
$app = new Application();

// 创建主窗口
$window = $app->createWindow("LibPHP 组件示例", 600, 400);

// 创建主容器
$mainBox = Box::newVerticalBox();
Box::setPadded($mainBox, true);

// 创建按钮组
$buttonGroup = new ButtonGroup(false, true);
$buttonGroup->addButton("按钮1", function ($btn, $index) use ($window) {
    $window->showMessage("提示", "按钮1被点击了");
})->addButton("按钮2", function ($btn, $index) use ($window) {
    $window->showMessage("提示", "按钮2被点击了");
});

// 将按钮组添加到主容器
Box::append($mainBox, $buttonGroup->getControl(), false);

// 设置窗口内容
$window->setContent($mainBox);

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    $app->quit();
    return true;
});

// 显示窗口并运行应用
$window->show();
$app->run();
```

更多示例请查看 [examples](examples/) 目录。

## 组件说明

### Application
应用程序管理器，用于初始化应用和管理窗口。

### WindowWrapper
窗口包装器，提供了更便捷的窗口操作方法。

### ButtonGroup
按钮组组件，可以方便地创建一组按钮并管理它们的事件。

### Form
表单组件，提供了创建表单的便捷方法，支持文本输入、密码输入等字段。

### CheckboxGroup
复选框组组件，可以方便地创建一组复选框并管理它们的状态和事件。

### SliderControl
滑块控制组件，提供了带标签和值显示的滑块控件。

### TabPanel
标签页面板组件，可以创建多标签页界面。

### MessageBox
消息框组件，提供了创建自定义消息框的便捷方法。

### ComboBox
下拉列表框组件，提供了带标签的下拉列表框控件，支持添加选项和选择事件。

### SpinBox
微调框组件，提供了带标签的微调框控件，支持设置范围和值改变事件。

### ProgressBar
进度条组件，提供了带标签和值显示的进度条控件，支持设置进度值。

### DateTimePicker
日期时间选择器组件，支持日期、时间或日期时间的选择。

### EditableComboBox
可编辑下拉列表框组件，用户既可以从下拉列表中选择选项，也可以输入自定义文本。

### MultilineEntry
多行文本输入框组件，支持多行文本输入，可设置是否自动换行和只读模式。

### Radio
单选按钮组组件，提供了一组互斥的选项，用户只能选择其中一个。

## 依赖

- PHP >= 8.2
- kingbes/libui ^0.0.2

## 许可证

MIT