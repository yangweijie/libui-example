# Project Context

## Purpose
LibPHP is a PHP GUI component library based on [kingbes/libui](https://github.com/KingBes/php-libui) that simplifies the development of PHP GUI applications. It encapsulates common GUI components and provides a more convenient API for creating desktop applications.

The project aims to make it easier for PHP developers to create cross-platform desktop applications with a consistent, modern UI component library.

## Tech Stack
- PHP >= 8.2
- kingbes/libui (underlying GUI library based on libui)
- FFI (for interacting with C libraries)
- kingbes/raylib ^0.0.5 (for game development components)

## Project Conventions

### Code Style
- Follow PSR-4 autoloading standard for component encapsulation
- Component class names match file names
- Use FFI to interact with the underlying libui C library
- Component methods尽量 provide chain call support (尽量提供链式调用支持)
- Component event callbacks implemented through anonymous functions

### Architecture Patterns
- Component-based architecture in `src/Components/`
- Each component encapsulates a specific UI element or functionality
- Separation of concerns between UI rendering and business logic
- Reusable components that can be easily integrated into applications

### Testing Strategy
- Component-level testing for individual UI elements
- Integration testing for component interactions
- Manual testing for visual components (due to GUI nature)
- Example-based testing in the `examples/` directory

### Git Workflow
- Feature branches for new components or significant changes
- Pull requests for code review
- Semantic versioning for releases
- Clear commit messages describing changes

## Domain Context
The project focuses on desktop GUI application development in PHP. Key domain concepts include:

- **Components**: Reusable UI elements like buttons, forms, sliders, etc.
- **Windows**: Application windows and their management
- **Events**: User interactions and system events
- **Layout**: Organization of UI elements within containers
- **Rendering**: Visual presentation of UI elements

## Important Constraints
- PHP version requirement: >= 8.2
- Dependency on kingbes/libui for underlying GUI functionality
- Cross-platform compatibility (macOS, Windows, Linux)
- Performance considerations for UI rendering
- Memory management when interacting with C libraries via FFI

## External Dependencies
- kingbes/libui: Core GUI library
- kingbes/raylib: Game development library (for game-related components)
- Composer: PHP dependency management