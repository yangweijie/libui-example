<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Entry;
use Kingbes\Libui\Control;
use \FFI\CData;

/**
 * 表单组件
 */
class Form
{
    protected CData $formBox;
    protected CData $form;
    protected array $fields = [];
    protected array $fieldCallbacks = [];

    /**
     * 构造函数
     *
     * @param bool $padded 是否有内边距
     */
    public function __construct(bool $padded = true)
    {
        $this->formBox = Box::newVerticalBox();
        Box::setPadded($this->formBox, $padded);
    }

    /**
     * 添加文本输入字段
     *
     * @param string $label 字段标签
     * @param string $defaultValue 默认值
     * @param bool $readOnly 是否只读
     * @param callable|null $callback 文本改变回调函数
     * @return self
     */
    public function addTextField(
        string $label,
        string $defaultValue = '',
        bool $readOnly = false,
        ?callable $callback = null
    ): self {
        $fieldBox = Box::newHorizontalBox();
        Box::setPadded($fieldBox, true);
        
        $labelControl = Label::create($label);
        $entryControl = Entry::create();
        Entry::setText($entryControl, $defaultValue);
        Entry::setReadOnly($entryControl, $readOnly);
        
        Box::append($fieldBox, $labelControl, false);
        Box::append($fieldBox, $entryControl, true);
        Box::append($this->formBox, $fieldBox, false);
        
        $index = count($this->fields);
        $this->fields[$index] = [
            'label' => $labelControl,
            'entry' => $entryControl,
            'type' => 'text'
        ];
        
        if ($callback !== null) {
            $this->fieldCallbacks[$index] = $callback;
            Entry::onChanged($entryControl, function ($entry) use ($callback, $index) {
                $callback($entry, $index);
            });
        }
        
        return $this;
    }

    /**
     * 添加密码输入字段
     *
     * @param string $label 字段标签
     * @param callable|null $callback 文本改变回调函数
     * @return self
     */
    public function addPasswordField(string $label, ?callable $callback = null): self
    {
        $fieldBox = Box::newHorizontalBox();
        Box::setPadded($fieldBox, true);
        
        $labelControl = Label::create($label);
        $entryControl = Entry::createPwd();
        
        Box::append($fieldBox, $labelControl, false);
        Box::append($fieldBox, $entryControl, true);
        Box::append($this->formBox, $fieldBox, false);
        
        $index = count($this->fields);
        $this->fields[$index] = [
            'label' => $labelControl,
            'entry' => $entryControl,
            'type' => 'password'
        ];
        
        if ($callback !== null) {
            $this->fieldCallbacks[$index] = $callback;
            Entry::onChanged($entryControl, function ($entry) use ($callback, $index) {
                $callback($entry, $index);
            });
        }
        
        return $this;
    }

    /**
     * 获取表单容器
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->formBox;
    }

    /**
     * 显示表单
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->formBox);
    }

    /**
     * 隐藏表单
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->formBox);
    }

    /**
     * 启用表单
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->formBox);
    }

    /**
     * 禁用表单
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->formBox);
    }

    /**
     * 获取字段值
     *
     * @param int $index 字段索引
     * @return string|null
     */
    public function getFieldValue(int $index): ?string
    {
        if (isset($this->fields[$index])) {
            return Entry::text($this->fields[$index]['entry']);
        }
        return null;
    }

    /**
     * 设置字段值
     *
     * @param int $index 字段索引
     * @param string $value 字段值
     * @return void
     */
    public function setFieldValue(int $index, string $value): void
    {
        if (isset($this->fields[$index])) {
            Entry::setText($this->fields[$index]['entry'], $value);
        }
    }

    /**
     * 获取所有字段值
     *
     * @return array
     */
    public function getAllFieldValues(): array
    {
        $values = [];
        foreach ($this->fields as $index => $field) {
            $values[$index] = Entry::text($field['entry']);
        }
        return $values;
    }

    /**
     * 设置字段是否只读
     *
     * @param int $index 字段索引
     * @param bool $readOnly 是否只读
     * @return void
     */
    public function setFieldReadOnly(int $index, bool $readOnly): void
    {
        if (isset($this->fields[$index])) {
            Entry::setReadOnly($this->fields[$index]['entry'], $readOnly);
        }
    }
}