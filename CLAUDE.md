# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This repository contains LibPHP, a PHP library that provides high-level GUI components built on top of kingbes/libui, which is a PHP FFI wrapper for the libui C library. The library simplifies the creation of cross-platform desktop GUI applications in PHP.

## Code Architecture

The library follows a component-based architecture where each GUI element is encapsulated in a PHP class:

1. **Core Components**:
   - `Application` - Main application manager that initializes libui and runs the main loop
   - `WindowWrapper` - Enhanced window component with additional convenience methods
   - `ButtonGroup` - Container for managing groups of buttons
   - `Form` - Form component for creating input forms with labels and fields
   - `CheckboxGroup` - Container for managing groups of checkboxes
   - `SliderControl` - Enhanced slider with label and value display
   - `TabPanel` - Tabbed interface component
   - `MessageBox` - Simplified message box creation
   - `ComboBox` - Dropdown selection component with label
   - `SpinBox` - Numeric input component with label
   - `ProgressBar` - Progress indicator with label and value
   - `DateTimePicker` - Date/time selection component
   - `EditableComboBox` - Editable dropdown component
   - `MultilineEntry` - Multi-line text input component
   - `Radio` - Radio button group component

2. **Dependencies**:
   - PHP >= 8.2
   - kingbes/libui ^0.0.2 (PHP FFI wrapper for libui C library)

## Common Development Tasks

### Running Examples

```bash
php examples/basic_example.php
php examples/extended_components_example.php
php examples/messagebox_example.php
php examples/more_components_example.php
```

### Component Development

1. Components are located in `src/Components/`
2. Each component wraps the underlying kingbes/libui functionality
3. Components typically expose a `getControl()` method to access the underlying libui control
4. Event handling is implemented through callback functions

## Key Implementation Patterns

1. **FFI Integration**: Components use `\FFI\CData` to interact with the underlying C library
2. **Method Chaining**: Many components support fluent interfaces for configuration
3. **Event Callbacks**: GUI events are handled through PHP callable functions
4. **Control Wrapping**: Higher-level components often wrap lower-level libui controls in containers for layout

## Best Practices

1. Always initialize the application before creating windows or controls
2. Properly handle window closing events to ensure clean application exit
3. Use the component wrapper classes for simplified GUI development
4. Follow the existing code style and patterns when adding new components

## Development Commands

### Installation
```bash
composer install
```

### Running Tests
```bash
# Run specific test files
php tests/DateTimePickerTest.php
php tests/DateTimePickerFFITest.php
php tests/DateTimePickerStructTmTest.php
```

### Component Development Workflow
1. Create new components in `src/Components/` following existing patterns
2. Add examples in the `examples/` directory to demonstrate usage
3. Test components by running example files
4. Follow the existing code style with method chaining and fluent interfaces