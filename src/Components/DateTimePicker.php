<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\DateTimePicker as LibuiDateTimePicker;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Control;
use \FFI\CData;
use Exception;

/**
 * 日期时间选择器组件
 */
class DateTimePicker
{
    protected CData $container;
    protected CData $dateTimePicker;
    protected string $type;
    protected $changeCallback = null;

    /**
     * 构造函数
     *
     * @param string|null $label 标签文本
     * @param string $type 类型 ('datetime', 'date', 'time')
     * @param bool $vertical 是否垂直排列
     */
    public function __construct(?string $label = null, string $type = 'datetime', bool $vertical = false)
    {
        $this->type = $type;
        
        // 创建容器
        $this->container = $vertical ? Box::newVerticalBox() : Box::newHorizontalBox();
        Box::setPadded($this->container, true);
        
        // 创建标签（如果提供了标签文本）
        if ($label !== null) {
            $labelControl = Label::create($label);
            Box::append($this->container, $labelControl, false);
        }
        
        // 创建日期时间选择器
        switch ($type) {
            case 'date':
                $this->dateTimePicker = LibuiDateTimePicker::createDate();
                break;
            case 'time':
                $this->dateTimePicker = LibuiDateTimePicker::createTime();
                break;
            case 'datetime':
            default:
                $this->dateTimePicker = LibuiDateTimePicker::createDataTime();
                break;
        }
        
        Box::append($this->container, $this->dateTimePicker, true);
    }

    /**
     * 获取日期时间选择器控件
     *
     * @return CData
     */
    public function getControl(): CData
    {
        return $this->container;
    }

    /**
     * 显示日期时间选择器控件
     *
     * @return void
     */
    public function show(): void
    {
        Control::show($this->container);
    }

    /**
     * 隐藏日期时间选择器控件
     *
     * @return void
     */
    public function hide(): void
    {
        Control::hide($this->container);
    }

    /**
     * 启用日期时间选择器控件
     *
     * @return void
     */
    public function enable(): void
    {
        Control::enable($this->container);
        Control::enable($this->dateTimePicker);
    }

    /**
     * 禁用日期时间选择器控件
     *
     * @return void
     */
    public function disable(): void
    {
        Control::disable($this->container);
        Control::disable($this->dateTimePicker);
    }

    /**
     * 获取日期时间
     *
     * @return array 包含日期时间信息的数组
     */
    public function getTime(): array
    {
        try {
            // 使用共享的 FFI 实例，避免架构不匹配问题
            $ffi = LibuiDateTimePicker::ffi();
            
            // 获取库文件路径，通过反射访问受保护的方法
            $reflection = new \ReflectionClass(LibuiDateTimePicker::class);
            $method = $reflection->getMethod('getLibFilePath');
            $method->setAccessible(true);
            $dllPath = $method->invoke(null);
            
            // 创建一个新的 FFI 实例，包含完整的 struct tm 定义和必要的函数声明
            $ffiWithStruct = \FFI::cdef('
                struct tm {
                    int tm_sec;
                    int tm_min;
                    int tm_hour;
                    int tm_mday;
                    int tm_mon;
                    int tm_year;
                    int tm_wday;
                    int tm_yday;
                    int tm_isdst;
                };
                
                typedef struct uiDateTimePicker uiDateTimePicker;
            ', $dllPath);
            
            // 创建 struct tm 实例
            $timeStruct = $ffiWithStruct->new('struct tm');
            
            // 调用底层库函数获取时间
            LibuiDateTimePicker::time($this->dateTimePicker, $timeStruct);
            
            // 将 C 结构体转换为 PHP 数组
            return [
                'second' => $timeStruct->tm_sec,
                'minute' => $timeStruct->tm_min,
                'hour' => $timeStruct->tm_hour,
                'day' => $timeStruct->tm_mday,
                'month' => $timeStruct->tm_mon,
                'year' => $timeStruct->tm_year,
                'weekday' => $timeStruct->tm_wday,
                'yearday' => $timeStruct->tm_yday,
                'isdst' => $timeStruct->tm_isdst
            ];
        } catch (Exception $e) {
            // 如果获取时间失败，返回默认值
            return [
                'second' => 0,
                'minute' => 0,
                'hour' => 0,
                'day' => 1,
                'month' => 0,
                'year' => 0,
                'weekday' => 0,
                'yearday' => 0,
                'isdst' => -1
            ];
        }
    }

    /**
     * 设置日期时间
     *
     * @param array $time 包含日期时间信息的数组
     * @return void
     */
    public function setTime(array $time): void
    {
        try {
            // 使用共享的 FFI 实例，避免架构不匹配问题
            $ffi = LibuiDateTimePicker::ffi();
            
            // 获取库文件路径，通过反射访问受保护的方法
            $reflection = new \ReflectionClass(LibuiDateTimePicker::class);
            $method = $reflection->getMethod('getLibFilePath');
            $method->setAccessible(true);
            $dllPath = $method->invoke(null);
            
            // 创建一个新的 FFI 实例，包含完整的 struct tm 定义和必要的函数声明
            $ffiWithStruct = \FFI::cdef('
                struct tm {
                    int tm_sec;
                    int tm_min;
                    int tm_hour;
                    int tm_mday;
                    int tm_mon;
                    int tm_year;
                    int tm_wday;
                    int tm_yday;
                    int tm_isdst;
                };
                
                typedef struct uiDateTimePicker uiDateTimePicker;
            ', $dllPath);
            
            // 创建 struct tm 实例
            $timeStruct = $ffiWithStruct->new('struct tm');
            
            // 将 PHP 数组转换为 C 结构体
            $timeStruct->tm_sec = $time['second'] ?? 0;
            $timeStruct->tm_min = $time['minute'] ?? 0;
            $timeStruct->tm_hour = $time['hour'] ?? 0;
            $timeStruct->tm_mday = $time['day'] ?? 1;
            $timeStruct->tm_mon = $time['month'] ?? 0;
            $timeStruct->tm_year = $time['year'] ?? 0;
            $timeStruct->tm_wday = $time['weekday'] ?? 0;
            $timeStruct->tm_yday = $time['yearday'] ?? 0;
            $timeStruct->tm_isdst = $time['isdst'] ?? -1; // -1 表示信息不可用
            
            // 调用底层库函数设置时间
            LibuiDateTimePicker::setTime($this->dateTimePicker, $timeStruct);
        } catch (Exception $e) {
            // 如果设置时间失败，静默处理
            // 可以根据需要记录日志或采取其他措施
        }
    }

    /**
     * 设置时间改变回调函数
     *
     * @param callable $callback 回调函数
     * @return self
     */
    public function onChange(callable $callback): self
    {
        $this->changeCallback = $callback;
        LibuiDateTimePicker::onChanged($this->dateTimePicker, function ($dateTimePicker) use ($callback) {
            $callback($dateTimePicker);
        });
        return $this;
    }

    public static function time($dateTimePicker){
        return LibuiDateTimePicker::time($dateTimePicker);
    }

    /**
     * 获取类型
     *
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}