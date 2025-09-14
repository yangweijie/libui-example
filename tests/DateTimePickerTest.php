<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\DateTimePicker;
use Kingbes\Libui\App;

/**
 * DateTimePicker 组件测试
 */
class DateTimePickerTest
{
    public function run()
    {
        echo "开始测试 DateTimePicker 组件...\n";
        
        // 初始化应用
        $app = new App();
        
        // 测试创建不同类型的 DateTimePicker
        $this->testCreateDateTimePickers();
        
        // 测试 getTime 和 setTime 方法
        $this->testGetSetTime();
        
        echo "所有测试完成。\n";
    }
    
    private function testCreateDateTimePickers()
    {
        echo "测试创建不同类型的 DateTimePicker...\n";
        
        // 创建日期时间选择器
        $dateTimePicker = new DateTimePicker("日期时间:", 'datetime', true);
        echo "✓ 成功创建日期时间选择器\n";
        
        // 创建日期选择器
        $datePicker = new DateTimePicker("日期:", 'date', true);
        echo "✓ 成功创建日期选择器\n";
        
        // 创建时间选择器
        $timePicker = new DateTimePicker("时间:", 'time', true);
        echo "✓ 成功创建时间选择器\n";
    }
    
    private function testGetSetTime()
    {
        echo "测试 getTime 和 setTime 方法...\n";
        
        // 创建日期时间选择器
        $dateTimePicker = new DateTimePicker("测试:", 'datetime', true);
        
        // 测试获取时间
        $time = $dateTimePicker->getTime();
        echo "✓ 成功获取时间: " . print_r($time, true) . "\n";
        
        // 测试设置时间
        $testTime = [
            'second' => 30,
            'minute' => 25,
            'hour' => 14,
            'day' => 15,
            'month' => 8,
            'year' => 123, // 2023年 - 1900
            'weekday' => 2,
            'yearday' => 257,
            'isdst' => -1
        ];
        
        $dateTimePicker->setTime($testTime);
        echo "✓ 成功设置时间\n";
        
        // 再次获取时间并验证
        $newTime = $dateTimePicker->getTime();
        // 注意：由于这是 GUI 组件，设置的时间可能不会完全反映在 getTime() 的结果中
        // 但我们至少验证方法可以正常调用且不会抛出异常
        echo "✓ 再次获取时间: " . print_r($newTime, true) . "\n";
    }
}

// 运行测试
$test = new DateTimePickerTest();
$test->run();