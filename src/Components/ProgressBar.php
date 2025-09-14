<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\ProgressBar as LibuiProgressBar;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 进度条组件
 */
class ProgressBar
{
    protected CData $container;
    protected CData $progressBar;
    protected CData $valueLabel;
    protected string $labelText;
    protected bool $showValue;

    /**
     * 构造函数
     *
     * @param string|null $label 标签文本
     * @param bool $showValue 是否显示当前值
     * @param bool $vertical 是否垂直排列
     */
    public function __construct(
        ?string $label = null,
        bool $showValue = true,
        bool $vertical = false
    ) {
        $this->labelText = $label ?? '';
        $this->showValue = $showValue;
        
        // 创建容器
        $this->container = $vertical ? Box::newVerticalBox() : Box::newHorizontalBox();
        Box::setPadded($this->container, true);
        
        // 创建标签（如果提供了标签文本）
        if ($label !== null) {
            $labelControl = Label::create($label);
            Box::append($this->container, $labelControl, false);
        }
        
        // 创建进度条
        $this->progressBar = LibuiProgressBar::create();
        Box::append($this->container, $this->progressBar, true);
        
        // 创建值显示标签（如果需要）
        if ($showValue) {
            $this->valueLabel = Label::create("0%");
            Box::append($this->container, $this->valueLabel, false);
        }
    }

    /**
     * 获取进度条控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示进度条控件
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏进度条控件
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用进度条控件
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
        Control::enable($this->progressBar);
    }

    /**
     * 禁用进度条控件
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
        Control::disable($this->progressBar);
    }

    /**
     * 获取进度条当前值
     *
     * @return int
     */
    public function getValue(): int
    {
        return LibuiProgressBar::value($this->progressBar);
    }

    /**
     * 设置进度条值
     *
     * @param int $value 值 (0-100)
     * @return void
     */
    public function setValue(int $value): void
    {
        // 确保值在有效范围内
        $value = max(0, min(100, $value));
        
        LibuiProgressBar::setValue($this->progressBar, $value);
        
        if ($this->showValue && isset($this->valueLabel)) {
            Label::setText($this->valueLabel, $value . "%");
        }
    }

    /**
     * 重置进度条值为0
     *
     * @return void
     */
    public function reset(): void
    {
        $this->setValue(0);
    }

    /**
     * 设置进度条为不确定状态（如果支持）
     *
     * @return void
     */
    public function setIndeterminate(): void
    {
        // libui 的进度条可能不支持不确定状态
        // 这里留作扩展用途
    }
}