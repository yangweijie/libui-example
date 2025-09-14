<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\DateTimePicker;
use Kingbes\Libui\DateTimePicker as LibuiDateTimePicker;

/**
 * DateTimePicker struct tm 测试
 * This test verifies that the FFI struct tm issue has been resolved
 */
class DateTimePickerStructTmTest
{
    public function run()
    {
        echo "Testing DateTimePicker FFI struct tm creation...\n";
        
        try {
            // Test 1: Verify we can create a DateTimePicker instance
            $dateTimePicker = new DateTimePicker("Test DateTime:", 'datetime', true);
            echo "✓ Successfully created DateTimePicker instance\n";
            
            // Test 2: Verify we can access the underlying control
            $control = $dateTimePicker->getControl();
            echo "✓ Successfully accessed DateTimePicker control\n";
            
            // Test 3: Test the FFI struct tm creation (the main fix)
            $this->testStructTmCreation();
            
            echo "All tests passed! The DateTimePicker FFI struct tm issue has been resolved.\n";
            
        } catch (Exception $e) {
            echo "✗ Test failed with exception: " . $e->getMessage() . "\n";
            return false;
        }
        
        return true;
    }
    
    private function testStructTmCreation()
    {
        echo "Testing FFI struct tm creation...\n";
        
        try {
            // Get the FFI instance
            $ffi = LibuiDateTimePicker::ffi();
            
            // Get the library file path using reflection (this was the key fix)
            $reflection = new \ReflectionClass(LibuiDateTimePicker::class);
            $method = $reflection->getMethod('getLibFilePath');
            $method->setAccessible(true);
            $dllPath = $method->invoke(null);
            
            // Create a new FFI instance with the complete struct tm definition
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
            
            // Create struct tm instance
            $timeStruct = $ffiWithStruct->new('struct tm');
            
            // Set and verify values
            $timeStruct->tm_sec = 30;
            $timeStruct->tm_min = 25;
            $timeStruct->tm_hour = 14;
            $timeStruct->tm_mday = 15;
            $timeStruct->tm_mon = 8;
            $timeStruct->tm_year = 123;
            $timeStruct->tm_wday = 2;
            $timeStruct->tm_yday = 257;
            $timeStruct->tm_isdst = -1;
            
            // Verify the values were set correctly
            if ($timeStruct->tm_sec === 30 &&
                $timeStruct->tm_min === 25 &&
                $timeStruct->tm_hour === 14 &&
                $timeStruct->tm_mday === 15 &&
                $timeStruct->tm_mon === 8 &&
                $timeStruct->tm_year === 123 &&
                $timeStruct->tm_wday === 2 &&
                $timeStruct->tm_yday === 257 &&
                $timeStruct->tm_isdst === -1) {
                echo "✓ Successfully created and manipulated struct tm\n";
            } else {
                throw new Exception("Failed to set struct tm values correctly");
            }
            
        } catch (Exception $e) {
            throw new Exception("FFI struct tm creation failed: " . $e->getMessage());
        }
    }
}

// Run the test
$test = new DateTimePickerStructTmTest();
exit($test->run() ? 0 : 1);