<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Table;
use Kingbes\Libui\TableValueType;

// 创建应用
$app = new Application();

// 创建主窗口
$window = $app->createWindow("修复后的表格测试", 400, 300, false);

// 创建主容器
$mainBox = Box::newVerticalBox();
Box::setPadded($mainBox, true);

// 添加标签
$label = Label::create("修复后的表格测试");
Box::append($mainBox, $label, false);

// 创建表格模型处理程序
$modelHandler = Table::modelHandler(
    2, // 列数
    TableValueType::String, // 列类型
    3, // 行数
    function ($handler, $row, $column) {
        // 返回单元格值
        return Table::createValueStr("R{$row}C{$column}");
    }
);

// 创建表格模型
$model = Table::createModel($modelHandler);

// 创建表格
$table = Table::create($model, -1); // -1 表示没有行背景颜色模型列

// 添加文本列
Table::appendTextColumn($table, "列1", 0, false, false);
Table::appendTextColumn($table, "列2", 1, false, false);

// 将表格添加到盒子中
Box::append($mainBox, $table, true);

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