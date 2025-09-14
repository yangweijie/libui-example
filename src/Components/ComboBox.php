<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Combobox as LibuiCombobox;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 下拉列表框组件
 */
class ComboBox
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
        
        // 创建下拉列表框
        $this->comboBox = LibuiCombobox::create();
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
        LibuiCombobox::append($this->comboBox, $text);
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
     * 获取下拉列表框控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示下拉列表框控件
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏下拉列表框控件
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用下拉列表框控件
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
        Control::enable($this->comboBox);
    }

    /**
     * 禁用下拉列表框控件
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
        Control::disable($this->comboBox);
    }

    /**
     * 获取选中项索引
     *
     * @return int
     */
    public function getSelectedIndex(): int
    {
        return LibuiCombobox::selected($this->comboBox);
    }

    /**
     * 设置选中项索引
     *
     * @param int $index 选中项索引
     * @return void
     */
    public function setSelectedIndex(int $index): void
    {
        if ($index >= 0 && $index < count($this->items)) {
            LibuiCombobox::setSelected($this->comboBox, $index);
        }
    }

    /**
     * 获取选中项文本
     *
     * @return string|null
     */
    public function getSelectedText(): ?string
    {
        $index = $this->getSelectedIndex();
        return $this->items[$index] ?? null;
    }

    /**
     * 设置选中项改变回调函数
     *
     * @param callable $callback 回调函数
     * @return self
     */
    public function onChange(callable $callback): self
    {
        $this->changeCallback = $callback;
        LibuiCombobox::onSelected($this->comboBox, function ($comboBox) use ($callback) {
            $index = $this->getSelectedIndex();
            $text = $this->getSelectedText();
            $callback($comboBox, $index, $text);
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