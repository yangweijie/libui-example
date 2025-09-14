<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\EditableCombobox as LibuiEditableCombobox;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 可编辑下拉列表框组件
 */
class EditableComboBox
{
    protected CData $container;
    protected CData $comboBox;
    protected array $items = [];
    protected $changeCallback = null;

    /**
     * 构造函数
     *
     * @param string|null $label 标签文本
     * @param bool $vertical 是否垂直排列
     */
    public function __construct(?string $label = null, bool $vertical = false)
    {
        // 创建容器
        $this->container = $vertical ? Box::newVerticalBox() : Box::newHorizontalBox();
        Box::setPadded($this->container, true);
        
        // 创建标签（如果提供了标签文本）
        if ($label !== null) {
            $labelControl = Label::create($label);
            Box::append($this->container, $labelControl, false);
        }
        
        // 创建可编辑下拉列表框
        $this->comboBox = LibuiEditableCombobox::create();
        Box::append($this->container, $this->comboBox, true);
    }

    /**
     * 添加选项
     *
     * @param string $text 选项文本
     * @return self
     */
    public function addItem(string $text): self
    {
        LibuiEditableCombobox::append($this->comboBox, $text);
        $this->items[] = $text;
        return $this;
    }

    /**
     * 批量添加选项
     *
     * @param array $items 选项数组
     * @return self
     */
    public function addItems(array $items): self
    {
        foreach ($items as $item) {
            $this->addItem($item);
        }
        return $this;
    }

    /**
     * 获取可编辑下拉列表框控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示可编辑下拉列表框控件
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏可编辑下拉列表框控件
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用可编辑下拉列表框控件
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
        Control::enable($this->comboBox);
    }

    /**
     * 禁用可编辑下拉列表框控件
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
        Control::disable($this->comboBox);
    }

    /**
     * 获取文本
     *
     * @return string
     */
    public function getText(): string
    {
        return LibuiEditableCombobox::text($this->comboBox);
    }

    /**
     * 设置文本
     *
     * @param string $text 文本
     * @return void
     */
    public function setText(string $text): void
    {
        LibuiEditableCombobox::setText($this->comboBox, $text);
    }

    /**
     * 设置文本改变回调函数
     *
     * @param callable $callback 回调函数
     * @return self
     */
    public function onChange(callable $callback): self
    {
        $this->changeCallback = $callback;
        LibuiEditableCombobox::onChanged($this->comboBox, function ($comboBox) use ($callback) {
            $text = $this->getText();
            $callback($comboBox, $text);
        });
        return $this;
    }

    /**
     * 获取所有选项
     *
     * @return array
     */
    public function getItems(): array
    {
        return $this->items;
    }
}