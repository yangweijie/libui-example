<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Box;
use Kingbes\Libui\Checkbox;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 复选框组组件
 */
class CheckboxGroup
{
    protected CData $container;
    protected array $checkboxes = [];
    protected array $checkboxCallbacks = [];

    /**
     * 构造函数
     *
     * @param bool $vertical 是否垂直排列，默认为垂直排列
     * @param bool $padded 是否有内边距
     */
    public function __construct(bool $vertical = true, bool $padded = true)
    {
        $this->container = $vertical ? Box::newVerticalBox() : Box::newHorizontalBox();
        Box::setPadded($this->container, $padded);
    }

    /**
     * 添加复选框
     *
     * @param string $label 复选框标签
     * @param bool $checked 是否选中
     * @param callable|null $callback 状态改变回调函数
     * @return self
     */
    public function addCheckbox(string $label, bool $checked = false, ?callable $callback = null): self
    {
        $checkbox = Checkbox::create($label);
        Checkbox::setChecked($checkbox, $checked);
        Box::append($this->container, $checkbox, false);
        
        $index = count($this->checkboxes);
        $this->checkboxes[$index] = $checkbox;
        
        if ($callback !== null) {
            $this->checkboxCallbacks[$index] = $callback;
            Checkbox::onToggled($checkbox, function ($cb) use ($callback, $index) {
                $isChecked = Checkbox::checked($cb);
                $callback($cb, $isChecked, $index);
            });
        }
        
        return $this;
    }

    /**
     * 获取容器控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示复选框组
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏复选框组
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用复选框组
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
    }

    /**
     * 禁用复选框组
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
    }

    /**
     * 获取指定索引的复选框状态
     *
     * @param int $index 复选框索引
     * @return bool|null
     */
    public function isChecked(int $index): ?bool
    {
        if (isset($this->checkboxes[$index])) {
            return Checkbox::checked($this->checkboxes[$index]);
        }
        return null;
    }

    /**
     * 设置指定索引的复选框状态
     *
     * @param int $index 复选框索引
     * @param bool $checked 是否选中
     * @return void
     */
    public function setChecked(int $index, bool $checked): void
    {
        if (isset($this->checkboxes[$index])) {
            Checkbox::setChecked($this->checkboxes[$index], $checked);
        }
    }

    /**
     * 获取所有选中的复选框索引
     *
     * @return array
     */
    public function getCheckedIndexes(): array
    {
        $checkedIndexes = [];
        foreach ($this->checkboxes as $index => $checkbox) {
            if (Checkbox::checked($checkbox)) {
                $checkedIndexes[] = $index;
            }
        }
        return $checkedIndexes;
    }

    /**
     * 获取所有选中的复选框标签
     *
     * @return array
     */
    public function getCheckedLabels(): array
    {
        $checkedLabels = [];
        foreach ($this->checkboxes as $index => $checkbox) {
            if (Checkbox::checked($checkbox)) {
                $checkedLabels[] = Checkbox::text($checkbox);
            }
        }
        return $checkedLabels;
    }

    /**
     * 全选
     *
     * @return void
     */
    public function checkAll(): void
    {
        foreach ($this->checkboxes as $checkbox) {
            Checkbox::setChecked($checkbox, true);
        }
    }

    /**
     * 全不选
     *
     * @return void
     */
    public function uncheckAll(): void
    {
        foreach ($this->checkboxes as $checkbox) {
            Checkbox::setChecked($checkbox, false);
        }
    }

    /**
     * 反选
     *
     * @return void
     */
    public function toggleAll(): void
    {
        foreach ($this->checkboxes as $checkbox) {
            $current = Checkbox::checked($checkbox);
            Checkbox::setChecked($checkbox, !$current);
        }
    }
}