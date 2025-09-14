<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Box;
use Kingbes\Libui\Button;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 按钮组组件
 */
class ButtonGroup
{
    protected CData $box;
    protected array $buttons = [];
    protected array $buttonCallbacks = [];

    /**
     * 构造函数
     *
     * @param bool $vertical 是否垂直排列，默认为水平排列
     * @param bool $padded 是否有内边距
     */
    public function __construct(bool $vertical = false, bool $padded = true)
    {
        $this->box = $vertical ? Box::newVerticalBox() : Box::newHorizontalBox();
        Box::setPadded($this->box, $padded);
    }

    /**
     * 添加按钮
     *
     * @param string $text 按钮文本
     * @param callable|null $callback 按钮点击回调函数
     * @param bool $stretchy 是否可拉伸
     * @return self
     */
    public function addButton(string $text, ?callable $callback = null, bool $stretchy = false): self
    {
        $button = Button::create($text);
        Box::append($this->box, $button, $stretchy);
        
        $index = count($this->buttons);
        $this->buttons[$index] = $button;
        
        if ($callback !== null) {
            $this->buttonCallbacks[$index] = $callback;
            Button::onClicked($button, function ($btn) use ($callback, $index) {
                $callback($btn, $index);
            });
        }
        
        return $this;
    }

    /**
     * 获取按钮组容器
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->box;
    }

    /**
     * 显示按钮组
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->box);
    }

    /**
     * 隐藏按钮组
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->box);
    }

    /**
     * 启用按钮组
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->box);
    }

    /**
     * 禁用按钮组
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->box);
    }

    /**
     * 获取指定索引的按钮
     *
     * @param int $index 按钮索引
     * @return CData|null
     */
    public function getButton(int $index): ?CData
    {
        return $this->buttons[$index] ?? null;
    }

    /**
     * 设置按钮文本
     *
     * @param int $index 按钮索引
     * @param string $text 按钮文本
     * @return void
     */
    public function setButtonText(int $index, string $text): void
    {
        if (isset($this->buttons[$index])) {
            Button::setText($this->buttons[$index], $text);
        }
    }

    /**
     * 获取按钮文本
     *
     * @param int $index 按钮索引
     * @return string|null
     */
    public function getButtonText(int $index): ?string
    {
        if (isset($this->buttons[$index])) {
            return Button::text($this->buttons[$index]);
        }
        return null;
    }
}