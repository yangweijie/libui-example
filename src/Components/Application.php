<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;

/**
 * 应用程序组件
 */
class Application
{
    protected bool $initialized = false;
    protected array $windows = [];

    /**
     * 初始化应用
     *
     * @return self
     */
    public function init(): self
    {
        if (!$this->initialized) {
            App::init();
            $this->initialized = true;
        }
        return $this;
    }

    /**
     * 创建窗口
     *
     * @param string $title 窗口标题
     * @param int $width 窗口宽度
     * @param int $height 窗口高度
     * @param bool $hasMenubar 是否有菜单栏
     * @return WindowWrapper
     */
    public function createWindow(
        string $title,
        int $width = 800,
        int $height = 600,
        bool $hasMenubar = false
    ): WindowWrapper {
        $this->init();
        $window = new WindowWrapper($title, $width, $height, $hasMenubar);
        $this->windows[] = $window;
        return $window;
    }

    /**
     * 运行应用主循环
     *
     * @return void
     */
    public function run(): void
    {
        if ($this->initialized) {
            // 注册应用程序退出事件
            App::onShouldQuit(function () {
                App::quit();
                return true;
            });
            App::main();
        }
    }

    /**
     * 退出应用
     *
     * @return void
     */
    public function quit(): void
    {
        if ($this->initialized) {
            App::quit();
        }
    }
}