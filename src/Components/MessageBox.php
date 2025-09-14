<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Window;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Button;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 消息框组件
 */
class MessageBox
{
    protected CData $window;
    protected CData $contentBox;
    protected CData $buttonBox;
    protected array $buttons = [];
    protected $closeCallback = null;

    /**
     * 构造函数
     *
     * @param string $title 窗口标题
     * @param string $message 消息内容
     * @param int $width 窗口宽度
     * @param int $height 窗口高度
     */
    public function __construct(
        string $title,
        string $message,
        int $width = 300,
        int $height = 150
    ) {
        // 创建窗口
        $this->window = Window::create($title, $width, $height, 0);
        Window::setMargined($this->window, true);
        
        // 创建内容容器
        $this->contentBox = Box::newVerticalBox();
        Box::setPadded($this->contentBox, true);
        
        // 添加消息标签
        $messageLabel = Label::create($message);
        Box::append($this->contentBox, $messageLabel, true);
        
        // 创建按钮容器
        $this->buttonBox = Box::newHorizontalBox();
        Box::setPadded($this->buttonBox, true);
        Box::append($this->contentBox, $this->buttonBox, false);
        
        // 设置窗口内容
        Window::setChild($this->window, $this->contentBox);
        
        // 设置窗口关闭事件
        Window::onClosing($this->window, function ($window) {
            if ($this->closeCallback) {
                ($this->closeCallback)();
            }
            return 1;
        });
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
        Box::append($this->buttonBox, $button, $stretchy);
        
        $index = count($this->buttons);
        $this->buttons[$index] = $button;
        
        if ($callback !== null) {
            Button::onClicked($button, function ($btn) use ($callback) {
                $callback($btn);
                // 点击按钮后关闭窗口
                Control::destroy($this->window);
            });
        } else {
            // 默认情况下点击按钮关闭窗口
            Button::onClicked($button, function ($btn) {
                Control::destroy($this->window);
            });
        }
        
        return $this;
    }

    /**
     * 显示消息框
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->window);
    }

    /**
     * 设置关闭回调函数
     *
     * @param callable $callback 关闭回调函数
     * @return self
     */
    public function onClose(callable $callback): self
    {
        $this->closeCallback = $callback;
        return $this;
    }

    /**
     * 获取窗口控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->window;
    }
}