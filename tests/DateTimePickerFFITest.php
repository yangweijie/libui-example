<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

use Kingbes\Libui\DateTimePicker as LibuiDateTimePicker;
use Kingbes\Libui\Base;

/**
 * DateTimePicker FFI 结构体测试
 * 这个测试专注于验证 FFI struct tm 的创建和使用，而不创建实际的 UI 组件
 */
class DateTimePickerFFITest
{
    public function run()
    {
        echo "开始测试 DateTimePicker FFI 结构体...\n";
        
        // 测试 FFI 实例获取
        $this->testFFIInstance();
        
        // 测试 struct tm 创建
        $this->testStructTmCreation();
        
        echo "所有 FFI 测试完成。\n";
    }
    
    private function testFFIInstance()
    {
        echo "测试获取 FFI 实例...\n";
        
        try {
            $ffi = LibuiDateTimePicker::ffi();
            echo "✓ 成功获取 FFI 实例\n";
            
            // 测试获取库文件路径
            $reflection = new \ReflectionClass(LibuiDateTimePicker::class);
            $method = $reflection->getMethod('getLibFilePath');
            $method->setAccessible(true);
            $dllPath = $method->invoke(null);
            echo "✓ 成功获取库文件路径: " . $dllPath . "\n";
        } catch (Exception $e) {
            echo "✗ 获取 FFI 实例失败: " . $e->getMessage() . "\n";
        }
    }
    
    private function testStructTmCreation()
    {
        echo "测试 struct tm 创建...\n";
        
        try {
            // 获取 FFI 实例
            $ffi = LibuiDateTimePicker::ffi();
            
            // 获取库文件路径
            $reflection = new \ReflectionClass(LibuiDateTimePicker::class);
            $method = $reflection->getMethod('getLibFilePath');
            $method->setAccessible(true);
            $dllPath = $method->invoke(null);
            
            // 创建一个新的 FFI 实例，包含完整的 struct tm 定义
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
            ', $dllPath);
            
            // 创建 struct tm 实例
            $timeStruct = $ffiWithStruct->new('struct tm');
            
            // 验证结构体字段
            $timeStruct->tm_sec = 30;
            $timeStruct->tm_min = 25;
            $timeStruct->tm_hour = 14;
            $timeStruct->tm_mday = 15;
            $timeStruct->tm_mon = 8;
            $timeStruct->tm_year = 123;
            $timeStruct->tm_wday = 2;
            $timeStruct->tm_yday = 257;
            $timeStruct->tm_isdst = -1;
            
            echo "✓ 成功创建并设置 struct tm 实例\n";
            echo "  秒: " . $timeStruct->tm_sec . "\n";
            echo "  分: " . $timeStruct->tm_min . "\n";
            echo "  时: " . $timeStruct->tm_hour . "\n";
            echo "  日: " . $timeStruct->tm_mday . "\n";
            echo "  月: " . $timeStruct->tm_mon . "\n";
            echo "  年: " . $timeStruct->tm_year . "\n";
            echo "  周几: " . $timeStruct->tm_wday . "\n";
            echo "  年中第几天: " . $timeStruct->tm_yday . "\n";
            echo "  夏令时: " . $timeStruct->tm_isdst . "\n";
            
        } catch (Exception $e) {
            echo "✗ 创建 struct tm 实例失败: " . $e->getMessage() . "\n";
        }
    }
}

// 运行测试
$test = new DateTimePickerFFITest();
$test->run();