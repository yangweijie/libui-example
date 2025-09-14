<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Radio as LibuiRadio;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 单选按钮组组件
 */
class Radio
{
    protected CData $container;
    protected CData $radio;
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
        
        // 创建单选按钮组
        $this->radio = LibuiRadio::create();
        Box::append($this->container, $this->radio, true);
    }

    /**
     * 添加选项
     *
     * @param string $text 选项文本
     * @return self
     */
    public function addItem(string $text): self
    {
        LibuiRadio::append($this->radio, $text);
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
     * 获取单选按钮组控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示单选按钮组控件
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏单选按钮组控件
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用单选按钮组控件
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
        Control::enable($this->radio);
    }

    /**
     * 禁用单选按钮组控件
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
        Control::disable($this->radio);
    }

    /**
     * 获取选中项索引
     *
     * @return int
     */
    public function getSelectedIndex(): int
    {
        return LibuiRadio::selected($this->radio);
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
            LibuiRadio::setSelected($this->radio, $index);
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
        LibuiRadio::onSelected($this->radio, function ($radio) use ($callback) {
            $index = $this->getSelectedIndex();
            $text = $this->getSelectedText();
            $callback($radio, $index, $text);
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