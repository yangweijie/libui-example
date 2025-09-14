<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Tab;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 标签页组件
 */
class TabPanel
{
    protected CData $tab;
    protected array $pages = [];

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->tab = Tab::create();
    }

    /**
     * 添加标签页
     *
     * @param string $name 标签页名称
     * @param CData $control 控件
     * @param bool $stretchy 是否可拉伸
     * @return self
     */
    public function addPage(string $name, CData $control, bool $stretchy = false): self
    {
        Tab::append($this->tab, $name, $control, $stretchy);
        $index = count($this->pages);
        $this->pages[$index] = [
            'name' => $name,
            'control' => $control
        ];
        return $this;
    }

    /**
     * 插入标签页
     *
     * @param int $index 插入位置
     * @param string $name 标签页名称
     * @param CData $control 控件
     * @param bool $stretchy 是否可拉伸
     * @return self
     */
    public function insertPage(int $index, string $name, CData $control, bool $stretchy = false): self
    {
        Tab::insertAt($this->tab, $name, $index, $control, $stretchy);
        array_splice($this->pages, $index, 0, [[
            'name' => $name,
            'control' => $control
        ]]);
        return $this;
    }

    /**
     * 删除标签页
     *
     * @param int $index 标签页索引
     * @return self
     */
    public function deletePage(int $index): self
    {
        if (isset($this->pages[$index])) {
            Tab::delete($this->tab, $index);
            unset($this->pages[$index]);
            $this->pages = array_values($this->pages);
        }
        return $this;
    }

    /**
     * 获取标签页控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->tab;
    }

    /**
     * 显示标签页
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->tab);
    }

    /**
     * 隐藏标签页
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->tab);
    }

    /**
     * 启用标签页
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->tab);
    }

    /**
     * 禁用标签页
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->tab);
    }

    /**
     * 获取标签页数量
     *
     * @return int
     */
    public function getPageCount(): int
    {
        return count($this->pages);
    }

    /**
     * 获取当前选中的标签页索引
     *
     * @return int
     */
    public function getCurrentPage(): int
    {
        return Tab::numPages($this->tab) > 0 ? 0 : -1;
    }

    /**
     * 设置当前选中的标签页
     *
     * @param int $index 标签页索引
     * @return void
     */
    public function setCurrentPage(int $index): void
    {
        // 注意：libui 的 Tab 组件可能没有直接设置当前页的方法
        // 这里留作扩展用途
    }
}