<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Spinbox as LibuiSpinbox;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 微调框组件
 */
class SpinBox
{
    protected CData $container;
    protected CData $spinBox;
    protected string $labelText;
    protected $changeCallback = null;

    /**
     * 构造函数
     *
     * @param string|null $label 标签文本
     * @param int $min 最小值
     * @param int $max 最大值
     * @param int $defaultValue 默认值
     * @param bool $vertical 是否垂直排列
     */
    public function __construct(
        ?string $label = null,
        int $min = 0,
        int $max = 100,
        int $defaultValue = 0,
        bool $vertical = false
    ) {
        $this->labelText = $label ?? '';
        
        // 创建容器
        $this->container = $vertical ? Box::newVerticalBox() : Box::newHorizontalBox();
        Box::setPadded($this->container, true);
        
        // 创建标签（如果提供了标签文本）
        if ($label !== null) {
            $labelControl = Label::create($label);
            Box::append($this->container, $labelControl, false);
        }
        
        // 创建微调框
        $this->spinBox = LibuiSpinbox::create($min, $max);
        LibuiSpinbox::setValue($this->spinBox, $defaultValue);
        Box::append($this->container, $this->spinBox, true);
    }

    /**
     * 获取微调框控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示微调框控件
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏微调框控件
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用微调框控件
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
        Control::enable($this->spinBox);
    }

    /**
     * 禁用微调框控件
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
        Control::disable($this->spinBox);
    }

    /**
     * 获取微调框当前值
     *
     * @return int
     */
    public function getValue(): int
    {
        return LibuiSpinbox::value($this->spinBox);
    }

    /**
     * 设置微调框值
     *
     * @param int $value 值
     * @return void
     */
    public function setValue(int $value): void
    {
        LibuiSpinbox::setValue($this->spinBox, $value);
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
        LibuiSpinbox::onChanged($this->spinBox, function ($spinBox) use ($callback) {
            $value = $this->getValue();
            $callback($spinBox, $value);
        });
        return $this;
    }

    /**
     * 设置微调框范围
     *
     * @param int $min 最小值
     * @param int $max 最大值
     * @return void
     */
    public function setRange(int $min, int $max): void
    {
        // 注意：libui 的微调框可能不支持动态修改范围
        // 这里需要重新创建微调框控件
        // 为简化实现，这里留作扩展用途
    }
}