# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

### Added
- Application 组件：应用程序管理器
- WindowWrapper 组件：窗口包装器
- ButtonGroup 组件：按钮组组件
- Form 组件：表单组件
- CheckboxGroup 组件：复选框组组件
- SliderControl 组件：滑块控制组件
- TabPanel 组件：标签页面板组件
- MessageBox 组件：消息框组件
- ComboBox 组件：下拉列表框组件
- SpinBox 组件：微调框组件
- ProgressBar 组件：进度条组件
- DateTimePicker 组件：日期时间选择器组件
- EditableComboBox 组件：可编辑下拉列表框组件
- MultilineEntry 组件：多行文本输入框组件
- Radio 组件：单选按钮组组件
- 扩展组件使用示例

### Changed
- 修正 composer.json 中的类型拼写错误（libary -> library）

### Fixed
- 修复 MessageBox 和 SliderControl 组件中 callable 类型声明的问题
- 修复 basic_example.php 中闭包函数作用域问题