

<?php
require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\TabPanel;
use Yangweijie\Libphp\Components\ComboBox;
use Yangweijie\Libphp\Components\SpinBox;
use Yangweijie\Libphp\Components\EditableComboBox;
use Yangweijie\Libphp\Components\MultilineEntry;
use Yangweijie\Libphp\Components\Radio;
use Kingbes\Libui\Box;
use Kingbes\Libui\Button;
use Kingbes\Libui\Label;
use Kingbes\Libui\Group;
use Kingbes\Libui\Separator;
use Kingbes\Libui\DateTimePicker;
use Kingbes\Libui\Checkbox;

$app = new Application();
$window = $app->createWindow("Control Gallery", 600, 500);

// 左侧控件区
$leftBox = Box::newVerticalBox();
Box::setPadded($leftBox, true);
Box::append($leftBox, Button::create("Button"), false);
Box::append($leftBox, Checkbox::create("Checkbox"), false); // 用按钮模拟
Box::append($leftBox, Label::create("Label"), false);
Box::append($leftBox, DateTimePicker::createDate(), false);
Box::append($leftBox, DateTimePicker::createTime(), false);
Box::append($leftBox, DateTimePicker::createDataTime(), false);
Box::append($leftBox, Button::createFont(), false); // 字体选择按钮
Box::append($leftBox, Button::createColor(), false); // 颜色选择按钮
Box::append($leftBox, Separator::createHorizontal(), false);

$leftGroup = Group::create("Basic Controls");
Group::setChild($leftGroup, $leftBox);
Group::setMargined($leftGroup, 1);

$numbersBox = Box::newVerticalBox();
Box::setPadded($numbersBox, true);
$spinBox = new SpinBox(null, 0, 100, 42, true);
Box::append($numbersBox, $spinBox->getControl(), false);
$slider1 = Kingbes\Libui\Slider::create(0, 100);
Box::append($numbersBox, $slider1, true);
$progressBar = Kingbes\Libui\ProgressBar::create();
Box::append($numbersBox, $progressBar, true);
$numbersGroup = Group::create("Numbers");
Group::setChild($numbersGroup, $numbersBox);
Group::setMargined($numbersGroup, 1);

$listsBox = Box::newVerticalBox();
Box::setPadded($listsBox, true);
$comboBox = new ComboBox(null, true);
$comboBox->addItems(["Combobox Item 1", "Combobox Item 2", "Combobox Item 3"])->setSelectedIndex(0);
Box::append($listsBox, $comboBox->getControl(), false);
$editableComboBox = new EditableComboBox(null, true);
$editableComboBox->addItems(["Editable Item 1", "Editable Item 2", "Editable Item 3"])->setText("");
Box::append($listsBox, $editableComboBox->getControl(), false);
$radioGroup = new Radio(null, true);
$radioGroup->addItems(["Radio Button 1", "Radio Button 2", "Radio Button 3"])->setSelectedIndex(0);
Box::append($listsBox, $radioGroup->getControl(), false);
$listsGroup = Group::create("Lists");
Group::setChild($listsGroup, $listsBox);
Group::setMargined($listsGroup, 1);


// Tab 区域
$tabPanel = new TabPanel();
$tabPage1 = Box::newVerticalBox();
Box::setPadded($tabPage1, true);
$multilineEntry = new MultilineEntry(null, true, true);
$multilineEntry->setText("Please enter your feelings");
Box::append($tabPage1, $multilineEntry->getControl(), true);
$tabPanel->addPage("Page 1", $tabPage1, true);
$tabPanel->addPage("Page 2", Box::newVerticalBox(), true);
$tabPanel->addPage("Page 3", Box::newVerticalBox(), true);

$rightBox = Box::newVerticalBox();
Box::setPadded($rightBox, true);
Box::append($rightBox, $numbersGroup, false);
Box::append($rightBox, $listsGroup, false);
Box::append($rightBox, $tabPanel->getControl(), false);

$mainBox = Box::newHorizontalBox();
Box::setPadded($mainBox, true);
Box::append($mainBox, $leftGroup, true);
Box::append($mainBox, $rightBox, true);


$window->setContent($mainBox);
$window->onClose(function ($window) use ($app) {
    $app->quit();
    return true;
});
$window->show();
$app->run();