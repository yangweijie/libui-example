<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Area as LibuiArea;
use Kingbes\Libui\Draw;
use Kingbes\Libui\Base;
use FFI\CData;

/**
 * Area 组件封装
 */
class Area extends Base
{
    protected CData $area;
    protected CData $handler;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->handler = LibuiArea::handler();
        $this->area = LibuiArea::create(\FFI::addr($this->handler));
    }

    /**
     * 获取 Area 控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->area;
    }

    /**
     * 设置绘制处理函数
     *
     * @param callable $drawCallback 绘制回调函数
     * @return self
     */
    public function onDraw(callable $drawCallback): self
    {
        // 设置绘制回调
        $this->handler->Draw = function ($handler, $area, $drawParams) use ($drawCallback) {
            $drawCallback($drawParams);
        };

        return $this;
    }

    /**
     * 设置鼠标事件处理函数
     *
     * @param callable $mouseCallback 鼠标事件回调函数
     * @return self
     */
    public function onMouse(callable $mouseCallback): self
    {
        // 设置鼠标事件回调
        $this->handler->MouseEvent = function ($handler, $area, $mouseEvent) use ($mouseCallback) {
            $mouseCallback($mouseEvent);
        };

        return $this;
    }

    /**
     * 设置键盘事件处理函数
     *
     * @param callable $keyCallback 键盘事件回调函数
     * @return self
     */
    public function onKey(callable $keyCallback): self
    {
        // 设置键盘事件回调
        $this->handler->KeyEvent = function ($handler, $area, $keyEvent) use ($keyCallback) {
            return $keyCallback($keyEvent);
        };

        return $this;
    }

    /**
     * 队列重绘
     *
     * @return void
     */
    public function queueRedraw(): void
    {
        LibuiArea::queueRedraw($this->area);
    }

    /**
     * 设置区域大小
     *
     * @param int $width 宽度
     * @param int $height 高度
     * @return void
     */
    public function setSize(int $width, int $height): void
    {
        LibuiArea::setSize($this->area, $width, $height);
    }
}