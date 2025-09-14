<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use \FFI\CData;
use Kingbes\Libui\App;

/**
 * 窗口包装器
 */
class WindowWrapper
{
    protected CData $window;
    protected ?CData $content = null;
    protected array $eventCallbacks = [];

    /**
     * 构造函数
     *
     * @param string $title 窗口标题
     * @param int $width 窗口宽度
     * @param int $height 窗口高度
     * @param bool $hasMenubar 是否有菜单栏
     */
    public function __construct(
        string $title,
        int $width = 800,
        int $height = 600,
        bool $hasMenubar = false
    ) {
        $this->window = Window::create($title, $width, $height, $hasMenubar ? 1 : 0);
        Window::setMargined($this->window, true);
    }

    /**
     * 设置窗口内容
     *
     * @param CData $content 内容控件
     * @return self
     */
    public function setContent(CData $content): self
    {
        $this->content = $content;
        Window::setChild($this->window, $content);
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

    /**
     * 显示窗口
     *
     * @return self
     */
    public function show(): self
    {
        Control::show($this->window);
        return $this;
    }

    /**
     * 隐藏窗口
     *
     * @return self
     */
    public function hide(): self
    {
        Control::hide($this->window);
        return $this;
    }

    /**
     * 设置窗口标题
     *
     * @param string $title 窗口标题
     * @return self
     */
    public function setTitle(string $title): self
    {
        Window::setTitle($this->window, $title);
        return $this;
    }

    /**
     * 获取窗口标题
     *
     * @return string
     */
    public function getTitle(): string
    {
        return Window::title($this->window);
    }

    /**
     * 设置窗口大小
     *
     * @param int $width 窗口宽度
     * @param int $height 窗口高度
     * @return self
     */
    public function setSize(int $width, int $height): self
    {
        Window::setContentSize($this->window, $width, $height);
        return $this;
    }

    /**
     * 设置窗口是否全屏
     *
     * @param bool $fullscreen 是否全屏
     * @return self
     */
    public function setFullscreen(bool $fullscreen): self
    {
        Window::setFullscreen($this->window, $fullscreen);
        return $this;
    }

    /**
     * 设置窗口是否无边框
     *
     * @param bool $borderless 是否无边框
     * @return self
     */
    public function setBorderless(bool $borderless): self
    {
        Window::setBorderless($this->window, $borderless);
        return $this;
    }

    /**
     * 设置窗口关闭事件回调
     *
     * @param callable $callback 关闭事件回调函数
     * @return self
     */
    public function onClose(callable $callback): self
    {
        Window::onClosing($this->window, function ($window) use ($callback) {
            $result = $callback($window);
            return $result === false ? 0 : 1;
        });
        return $this;
    }

    /**
     * 设置窗口大小改变事件回调
     *
     * @param callable $callback 大小改变事件回调函数
     * @return self
     */
    public function onResize(callable $callback): self
    {
        Window::onContentSizeChanged($this->window, function ($window, $data) use ($callback) {
            $callback($window, $data);
        });
        return $this;
    }

    public function onClosing(callable $callback){
        // 窗口关闭事件
        Window::onClosing($this->window, function ($window) use($callback){
            if($callback($window)){
                App::quit();
                return 1;
            }else{
                return 0;
            }
        });
    }

    /**
     * 显示消息框
     *
     * @param string $title 消息框标题
     * @param string $message 消息内容
     * @return void
     */
    public function showMessage(string $title, string $message): void
    {
        Window::msgBox($this->window, $title, $message);
    }

    /**
     * 显示错误消息框
     *
     * @param string $title 消息框标题
     * @param string $message 错误内容
     * @return void
     */
    public function showError(string $title, string $message): void
    {
        Window::msgBoxError($this->window, $title, $message);
    }

    /**
     * 打开文件对话框
     *
     * @return string
     */
    public function openFile(): string
    {
        return Window::openFile($this->window);
    }

    /**
     * 保存文件对话框
     *
     * @return string
     */
    public function saveFile(): string
    {
        return Window::saveFile($this->window);
    }
}