<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\MultilineEntry as LibuiMultilineEntry;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 多行文本输入框组件
 */
class MultilineEntry
{
    protected CData $container;
    protected CData $entry;
    protected bool $wrapping;
    protected $changeCallback = null;

    /**
     * 构造函数
     *
     * @param string|null $label 标签文本
     * @param bool $wrapping 是否自动换行
     * @param bool $vertical 是否垂直排列
     */
    public function __construct(?string $label = null, bool $wrapping = true, bool $vertical = false)
    {
        $this->wrapping = $wrapping;
        
        // 创建容器
        $this->container = $vertical ? Box::newVerticalBox() : Box::newHorizontalBox();
        Box::setPadded($this->container, true);
        
        // 创建标签（如果提供了标签文本）
        if ($label !== null) {
            $labelControl = Label::create($label);
            Box::append($this->container, $labelControl, false);
        }
        
        // 创建多行文本输入框
        $this->entry = $wrapping ? LibuiMultilineEntry::create() : LibuiMultilineEntry::createNonWrapping();
        Box::append($this->container, $this->entry, true);
    }

    /**
     * 获取多行文本输入框控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示多行文本输入框控件
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏多行文本输入框控件
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用多行文本输入框控件
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
        Control::enable($this->entry);
    }

    /**
     * 禁用多行文本输入框控件
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
        Control::disable($this->entry);
    }

    /**
     * 获取文本
     *
     * @return string
     */
    public function getText(): string
    {
        return LibuiMultilineEntry::text($this->entry);
    }

    /**
     * 设置文本
     *
     * @param string $text 文本
     * @return void
     */
    public function setText(string $text): void
    {
        LibuiMultilineEntry::setText($this->entry, $text);
    }

    /**
     * 追加文本
     *
     * @param string $text 文本
     * @return void
     */
    public function appendText(string $text): void
    {
        LibuiMultilineEntry::append($this->entry, $text);
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
        LibuiMultilineEntry::onChanged($this->entry, function ($entry) use ($callback) {
            $text = $this->getText();
            $callback($entry, $text);
        });
        return $this;
    }

    /**
     * 获取是否只读
     *
     * @return bool
     */
    public function isReadOnly(): bool
    {
        return LibuiMultilineEntry::readOnly($this->entry);
    }

    /**
     * 设置是否只读
     *
     * @param bool $readOnly 是否只读
     * @return void
     */
    public function setReadOnly(bool $readOnly): void
    {
        LibuiMultilineEntry::setReadOnly($this->entry, $readOnly);
    }

    /**
     * 获取是否自动换行
     *
     * @return bool
     */
    public function isWrapping(): bool
    {
        return $this->wrapping;
    }
}