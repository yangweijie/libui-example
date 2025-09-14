<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Menu as LibuiMenu;
use Kingbes\Libui\MenuItem;
use Kingbes\Libui\Window;
use Kingbes\Libui\App;
use \FFI\CData;

/**
 * 菜单组件
 */
class Menu
{
    protected CData $menu;
    protected array $items = [];
    protected array $itemCallbacks = [];

    /**
     * 构造函数
     *
     * @param string $name 菜单名称
     */
    public function __construct(string $name)
    {
        $this->menu = LibuiMenu::create($name);
    }

    /**
     * 添加菜单项
     *
     * @param string $name 菜单项名称
     * @param callable|null $callback 点击回调函数
     * @return self
     */
    public function addItem(string $name, ?callable $callback = null): self
    {
        $item = LibuiMenu::appendItem($this->menu, $name);
        
        $index = count($this->items);
        $this->items[$index] = $item;
        
        if ($callback !== null) {
            $this->itemCallbacks[$index] = $callback;
            MenuItem::onClicked($item, function ($menuItem) use ($callback) {
                $callback($menuItem);
            });
        }
        
        return $this;
    }

    /**
     * 添加复选菜单项
     *
     * @param string $name 菜单项名称
     * @param bool $checked 是否选中
     * @param callable|null $callback 点击回调函数
     * @return self
     */
    public function addCheckItem(string $name, bool $checked = false, ?callable $callback = null): self
    {
        $item = LibuiMenu::appendCheckItem($this->menu, $name);
        MenuItem::setChecked($item, $checked);
        
        $index = count($this->items);
        $this->items[$index] = $item;
        
        if ($callback !== null) {
            $this->itemCallbacks[$index] = $callback;
            MenuItem::onClicked($item, function ($menuItem) use ($callback) {
                $isChecked = MenuItem::checked($menuItem);
                $callback($menuItem, $isChecked);
            });
        }
        
        return $this;
    }

    /**
     * 添加退出菜单项
     *
     * @return self
     */
    public function addQuitItem(): self
    {
        $item = LibuiMenu::appendQuitItem($this->menu);
        
        $index = count($this->items);
        $this->items[$index] = $item;
        
        // 退出菜单项不需要设置回调，因为它会自动退出应用
        return $this;
    }

    /**
     * 添加首选项菜单项
     *
     * @param callable|null $callback 点击回调函数
     * @return self
     */
    public function addPreferencesItem(?callable $callback = null): self
    {
        $item = LibuiMenu::appendPreferencesItem($this->menu);
        
        $index = count($this->items);
        $this->items[$index] = $item;
        
        if ($callback !== null) {
            $this->itemCallbacks[$index] = $callback;
            MenuItem::onClicked($item, function ($menuItem) use ($callback) {
                $callback($menuItem);
            });
        }
        
        return $this;
    }

    /**
     * 添加关于菜单项
     *
     * @param callable|null $callback 点击回调函数
     * @return self
     */
    public function addAboutItem(?callable $callback = null): self
    {
        $item = LibuiMenu::appendAboutItem($this->menu);
        
        $index = count($this->items);
        $this->items[$index] = $item;
        
        if ($callback !== null) {
            $this->itemCallbacks[$index] = $callback;
            MenuItem::onClicked($item, function ($menuItem) use ($callback) {
                $callback($menuItem);
            });
        }
        
        return $this;
    }

    /**
     * 添加分隔线
     *
     * @return self
     */
    public function addSeparator(): self
    {
        LibuiMenu::appendSeparator($this->menu);
        return $this;
    }

    /**
     * 启用菜单项
     *
     * @param int $index 菜单项索引
     * @return void
     */
    public function enableItem(int $index): void
    {
        if (isset($this->items[$index])) {
            MenuItem::enable($this->items[$index]);
        }
    }

    /**
     * 禁用菜单项
     *
     * @param int $index 菜单项索引
     * @return void
     */
    public function disableItem(int $index): void
    {
        if (isset($this->items[$index])) {
            MenuItem::disable($this->items[$index]);
        }
    }

    /**
     * 设置菜单项是否选中（仅适用于复选菜单项）
     *
     * @param int $index 菜单项索引
     * @param bool $checked 是否选中
     * @return void
     */
    public function setItemChecked(int $index, bool $checked): void
    {
        if (isset($this->items[$index])) {
            MenuItem::setChecked($this->items[$index], $checked);
        }
    }

    /**
     * 获取菜单项是否选中（仅适用于复选菜单项）
     *
     * @param int $index 菜单项索引
     * @return bool|null
     */
    public function isItemChecked(int $index): ?bool
    {
        if (isset($this->items[$index])) {
            return MenuItem::checked($this->items[$index]);
        }
        return null;
    }

    /**
     * 获取菜单句柄
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->menu;
    }
}