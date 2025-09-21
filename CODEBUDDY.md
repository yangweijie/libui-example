# CODEBUDDY.md

This file guides CodeBuddy Code for working in this repository.

## Common Commands

Installation & setup:
```bash
composer install
composer update
composer dump-autoload
php scripts/check-libui-dylib.php
```

Run examples:
```bash
php examples/basic_example.php
php examples/extended_components_example.php
```  

Run tests:
```bash
php tests/DateTimePickerTest.php
php tests/DateTimePickerFFITest.php
php tests/DateTimePickerStructTmTest.php
```

## Project Layout

- src/Components/  – PSR-4 GUI component classes wrapping kingbes/libui via FFI  
- examples/       – standalone scripts demonstrating usage  
- scripts/        – post-install/update checks (`check-libui-dylib.php`)  
- tests/          – simple PHP test runners for key components  
- vendor/         – dependencies managed by Composer

## Architecture Overview

LibPHP provides high-level GUI components on top of kingbes/libui (PHP-FFI for libui C library).  
Each widget class exposes a fluent interface and a `getControl()` method returning the underlying `\FFI\CData`.  
Event handling is done via PHP callables registered on the wrapper classes.

Autoloading:
- PSR-4 namespace `Yangweijie\Libphp\` → `src/`
- Defined in composer.json

## Composer Scripts

Composer runs `php scripts/check-libui-dylib.php` on install/update to verify libui dynamic library presence.

## References

- See `README.md` for component list and examples
- See `CLAUDE.md` for deeper implementation patterns: FFI integration, method chaining, event callbacks