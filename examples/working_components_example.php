<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Button;
use Kingbes\Libui\Entry;
use Kingbes\Libui\Group;
use Kingbes\Libui\Separator;
use Kingbes\Libui\Grid;
use Kingbes\Libui\Table;
use Kingbes\Libui\TableValueType;
use Kingbes\Libui\Align;

// 创建应用
$app = new Application();

// 创建主窗口（不带菜单）
$window = $app->createWindow("工作组件示例", 600, 400, false);

// 创建标签页容器
$tabContainer = Box::newVerticalBox();
Box::setPadded($tabContainer, true);

// 创建标签按钮容器
$tabButtons = Box::newHorizontalBox();
Box::setPadded($tabButtons, true);

// 创建内容容器
$contentContainer = Box::newVerticalBox();
Box::setPadded($contentContainer, true);

// 添加标签按钮和内容容器到主容器
Box::append($tabContainer, $tabButtons, false);
Box::append($tabContainer, $contentContainer, true);

// 创建各个标签页的内容
// 标签页1：Entry组件示例
$tab1Content = Box::newVerticalBox();
Box::setPadded($tab1Content, true);

$label1 = Label::create("Entry组件示例");
Box::append($tab1Content, $label1, false);

$entry = Entry::create();
Entry::setText($entry, "输入一些文本");
Box::append($tab1Content, $entry, false);

$button1 = Button::create("获取文本");
Button::onClicked($button1, function() use ($entry) {
    $text = Entry::text($entry);
    echo "输入的文本: " . $text . "\n";
});
Box::append($tab1Content, $button1, false);

// 标签页2：Group、Separator和Grid组件示例
$tab2Content = Box::newVerticalBox();
Box::setPadded($tab2Content, true);

$label2 = Label::create("Group、Separator和Grid组件示例");
Box::append($tab2Content, $label2, false);

// 创建Group
$group = Group::create("分组标题");
Group::setMargined($group, true);

// 创建Grid
$grid = Grid::create();
Grid::setPadded($grid, true);

// 在Grid中添加控件
$labelInGrid1 = Label::create("网格标签1");
$labelInGrid2 = Label::create("网格标签2");
$buttonInGrid1 = Button::create("网格按钮1");
$buttonInGrid2 = Button::create("网格按钮2");

Grid::append($grid, $labelInGrid1, 0, 0, 1, 1, 1, 0, 1, Align::Fill);
Grid::append($grid, $labelInGrid2, 1, 0, 1, 1, 1, 0, 1, Align::Fill);
Grid::append($grid, $buttonInGrid1, 0, 1, 1, 1, 1, 0, 1, Align::Fill);
Grid::append($grid, $buttonInGrid2, 1, 1, 1, 1, 1, 0, 1, Align::Fill);

// 将Grid添加到Group
Group::setChild($group, $grid);

// 将Group添加到标签页
Box::append($tab2Content, $group, false);

// 添加Separator
$separator = Separator::createHorizontal();
Box::append($tab2Content, $separator, false);

// 标签页3：表格组件示例
$tab3Content = Box::newVerticalBox();
Box::setPadded($tab3Content, true);

$label3 = Label::create("表格组件示例");
Box::append($tab3Content, $label3, false);

// 创建表格模型处理程序
$modelHandler = Table::modelHandler(
    3, // 列数
    TableValueType::String, // 列类型
    5, // 行数
    function ($mh, $m, $row, $column) {
        // 返回单元格值
        $data = [
            ["张三", "25", "工程师"],
            ["李四", "30", "设计师"],
            ["王五", "28", "产品经理"],
            ["赵六", "35", "项目经理"],
            ["孙七", "27", "测试工程师"]
        ];
        return Table::createValueStr($data[$row][$column]);
    }
);

// 创建表格模型
$model = Table::createModel($modelHandler);

// 创建表格
$table = Table::create($model, -1); // -1 表示没有行背景颜色模型列

// 添加文本列
Table::appendTextColumn($table, "姓名", 0, false);
Table::appendTextColumn($table, "年龄", 1, false);
Table::appendTextColumn($table, "职位", 2, false);

// 将表格添加到标签页
Box::append($tab3Content, $table, true);

// 创建标签按钮
$tabButton1 = Button::create("Entry组件");
$tabButton2 = Button::create("Group/Grid");
$tabButton3 = Button::create("表格");

// 设置标签按钮点击事件
Button::onClicked($tabButton1, function() use ($contentContainer, $tab1Content) {
    // 清空内容容器并添加新内容
    // 注意：这里简化处理，实际应用中可能需要更复杂的容器管理
    echo "显示Entry组件标签页\n";
});

Button::onClicked($tabButton2, function() use ($contentContainer, $tab2Content) {
    echo "显示Group/Grid标签页\n";
});

Button::onClicked($tabButton3, function() use ($contentContainer, $tab3Content) {
    echo "显示表格标签页\n";
});

// 将标签按钮添加到标签按钮容器
Box::append($tabButtons, $tabButton1, true);
Box::append($tabButtons, $tabButton2, true);
Box::append($tabButtons, $tabButton3, true);

// 设置默认显示第一个标签页的内容
Box::append($contentContainer, $tab1Content, true);

// 设置窗口内容
$window->setContent($tabContainer);

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