<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Slider;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 滑块控制组件
 */
class SliderControl
{
    protected CData $container;
    protected CData $slider;
    protected CData $valueLabel;
    protected string $labelText;
    protected $changeCallback = null;

    /**
     * 构造函数
     *
     * @param string $label 标签文本
     * @param int $min 最小值
     * @param int $max 最大值
     * @param int $defaultValue 默认值
     * @param bool $showValue 是否显示当前值
     * @param bool $vertical 是否垂直排列
     */
    public function __construct(
        string $label,
        int $min = 0,
        int $max = 100,
        int $defaultValue = 0,
        bool $showValue = true,
        bool $vertical = false
    ) {
        $this->labelText = $label;
        
        // 创建容器
        $this->container = $vertical ? Box::newVerticalBox() : Box::newHorizontalBox();
        Box::setPadded($this->container, true);
        
        // 创建标签
        $labelControl = Label::create($label);
        Box::append($this->container, $labelControl, false);
        
        // 创建滑块
        $this->slider = Slider::create($min, $max);
        Slider::setValue($this->slider, $defaultValue);
        Box::append($this->container, $this->slider, true);
        
        // 创建值显示标签（如果需要）
        if ($showValue) {
            $this->valueLabel = Label::create((string)$defaultValue);
            Box::append($this->container, $this->valueLabel, false);
        }
        
        // 设置滑块事件
        Slider::onChanged($this->slider, function ($slider) {
            $value = Slider::value($slider);
            if ($this->valueLabel) {
                Label::setText($this->valueLabel, (string)$value);
            }
            if ($this->changeCallback) {
                ($this->changeCallback)($value);
            }
        });
    }

    /**
     * 获取滑块控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示滑块控件
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏滑块控件
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用滑块控件
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
        Control::enable($this->slider);
    }

    /**
     * 禁用滑块控件
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
        Control::disable($this->slider);
    }

    /**
     * 获取滑块当前值
     *
     * @return int
     */
    public function getValue(): int
    {
        return Slider::value($this->slider);
    }

    /**
     * 设置滑块值
     *
     * @param int $value 值
     * @return void
     */
    public function setValue(int $value): void
    {
        Slider::setValue($this->slider, $value);
        if ($this->valueLabel) {
            Label::setText($this->valueLabel, (string)$value);
        }
    }

    /**
     * 设置值改变回调函数
     *
     * @param callable $callback 回调函数
     * @return self
     */
    public function onChange(callable $callback): self
    {
        $this->changeCallback = $callback;
        return $this;
    }

    /**
     * 设置滑块范围
     *
     * @param int $min 最小值
     * @param int $max 最大值
     * @return void
     */
    public function setRange(int $min, int $max): void
    {
        // 注意：libui 的滑块可能不支持动态修改范围
        // 这里需要重新创建滑块控件
        // 为简化实现，这里留作扩展用途
    }
}