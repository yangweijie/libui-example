# LibPHP 项目说明

## 项目概述

LibPHP 是一个基于 [kingbes/libui](https://github.com/KingBes/php-libui) 的 PHP GUI 组件库，旨在简化 PHP GUI 应用程序的开发。它封装了常用的 GUI 组件，提供了更便捷的 API 来创建桌面应用程序。

主要技术栈：
- PHP >= 8.2
- kingbes/libui (底层 GUI 库，基于 libui)
- FFI (用于与 C 库交互)

项目架构：
- `src/Components/`: 核心组件封装
- `examples/`: 使用示例
- `scripts/`: 辅助脚本（如 libui.dylib 检查和替换）
- `kingbes/libui/lib/macos/`: macOS 平台的 libui 动态库

## 核心组件

1.  **Application**: 应用程序管理器，负责初始化应用和管理窗口。
2.  **WindowWrapper**: 窗口包装器，提供了更便捷的窗口操作方法。
3.  **ButtonGroup**: 按钮组组件，可以方便地创建一组按钮并管理它们的事件。
4.  **Form**: 表单组件，提供了创建表单的便捷方法，支持文本输入、密码输入等字段。
5.  **CheckboxGroup**: 复选框组组件，可以方便地创建一组复选框并管理它们的状态和事件。
6.  **SliderControl**: 滑块控制组件，提供了带标签和值显示的滑块控件。
7.  **TabPanel**: 标签页面板组件，可以创建多标签页界面。
8.  **MessageBox**: 消息框组件，提供了创建自定义消息框的便捷方法。
9.  **ComboBox**: 下拉列表框组件，提供了带标签的下拉列表框控件，支持添加选项和选择事件。
10. **SpinBox**: 微调框组件，提供了带标签的微调框控件，支持设置范围和值改变事件。
11. **ProgressBar**: 进度条组件，提供了带标签和值显示的进度条控件，支持设置进度值。
12. **DateTimePicker**: 日期时间选择器组件，支持日期、时间或日期时间的选择。
13. **EditableComboBox**: 可编辑下拉列表框组件，用户既可以从下拉列表中选择选项，也可以输入自定义文本。
14. **MultilineEntry**: 多行文本输入框组件，支持多行文本输入，可设置是否自动换行和只读模式。
15. **Radio**: 单选按钮组组件，提供了一组互斥的选项，用户只能选择其中一个。

## 构建和运行

### 环境要求

- PHP >= 8.2
- macOS ARM 架构（对于 macOS 用户，项目包含特定的 libui.dylib 文件处理）

### 安装

```bash
composer require yangweijie/libphp
```

### 运行示例

1. 确保已安装依赖：
   ```bash
   composer install
   ```

2. 运行示例（例如 basic_example.php）：
   ```bash
   php examples/basic_example.php
   ```

### 项目特定脚本

项目在 `composer.json` 中定义了以下脚本，在安装或更新后会自动运行：
- `post-install-cmd`: 运行 `scripts/check-libui-dylib.php` 脚本
- `post-update-cmd`: 运行 `scripts/check-libui-dylib.php` 脚本

`check-libui-dylib.php` 脚本用于检查 macOS ARM 系统上的 `libui.dylib` 文件，并在 MD5 不匹配时进行替换。

## 开发约定

- 组件封装遵循 PSR-4 自动加载标准。
- 组件类名与文件名一致。
- 使用 FFI 与底层 libui C 库交互。
- 组件方法尽量提供链式调用支持。
- 组件事件回调通过匿名函数实现。