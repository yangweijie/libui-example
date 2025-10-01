# Implementation Plan

- [ ] 1. Create core data structures and piece definitions
  - Implement TetrisPiece class with shape definitions and basic operations
  - Define piece shapes, colors, and rotation logic
  - Create unit tests for piece rotation and collision detection
  - _Requirements: 5.1, 5.2_

- [ ] 2. Implement game state management
  - Create GameState class with grid initialization and piece management
  - Implement piece spawning, movement validation, and locking logic
  - Add line clearing algorithm with proper grid manipulation
  - Create unit tests for game state operations
  - _Requirements: 1.3, 3.1, 3.2, 3.3, 4.1, 4.2_

- [ ] 3. Build scoring system
  - Implement score calculation for different line clear combinations
  - Add score tracking and display functionality
  - Create tests for scoring logic validation
  - _Requirements: 4.3, 4.4, 4.5, 4.6_

- [ ] 4. Create game renderer with libui Draw API
  - Implement GameRenderer class using libui Area and Draw components
  - Add methods for drawing grid, pieces, and game elements
  - Implement color mapping and block rendering
  - Create visual tests for rendering accuracy
  - _Requirements: 1.1, 1.2, 5.1, 5.2, 5.3, 7.1, 7.4_

- [ ] 5. Implement input handling system
  - Create InputHandler class for WASD key processing
  - Add piece movement, rotation, and drop functionality
  - Implement collision detection integration with input validation
  - Create tests for input response and validation
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5_

- [ ] 6. Build automatic piece falling mechanism
  - Implement timer-based automatic piece descent
  - Add piece locking when collision occurs during fall
  - Integrate with game state for continuous piece spawning
  - Create tests for timing and fall behavior
  - _Requirements: 3.1, 3.2, 3.3_

- [ ] 7. Create main game controller
  - Implement TetrisGame class as main application controller
  - Set up libui window and area components
  - Integrate all game systems (state, renderer, input, timer)
  - Add proper initialization and cleanup procedures
  - _Requirements: 1.1, 7.1, 7.2, 7.3_

- [ ] 8. Implement game over and restart functionality
  - Add game over detection when pieces cannot spawn
  - Create game over overlay display
  - Implement restart functionality with space bar
  - Add proper game state reset and cleanup
  - Create tests for game over conditions and restart
  - _Requirements: 3.4, 6.1, 6.2, 6.3, 6.4_

- [ ] 9. Add UI information display
  - Implement next piece preview display
  - Add score display in game interface
  - Create control instructions display
  - Ensure proper layout and positioning
  - _Requirements: 1.4, 7.2_

- [ ] 10. Create complete application entry point
  - Build main application file (tetris2.php) with proper autoloading
  - Initialize all components and start game loop
  - Add proper error handling and resource cleanup
  - Integrate with existing libui component structure
  - _Requirements: 1.1, 7.1, 7.3_

- [ ] 11. Implement comprehensive testing suite
  - Create unit tests for all game logic components
  - Add integration tests for complete game flow
  - Implement manual testing procedures for UI validation
  - Create performance tests for rendering and game loop
  - _Requirements: All requirements validation_

- [ ] 12. Add final polish and optimization
  - Optimize rendering performance and memory usage
  - Fine-tune game timing and responsiveness
  - Add proper documentation and code comments
  - Validate all WASD controls and game mechanics
  - _Requirements: 2.1, 2.2, 2.3, 2.4, 7.4_