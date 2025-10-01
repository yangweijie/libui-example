# Design Document

## Overview

The libui-based Tetris implementation will leverage the kingbes/libui library's drawing capabilities through the Area component and Draw API. The design follows a component-based architecture with clear separation between game logic, rendering, and user input handling. The game will use a custom drawing area for the game grid and standard libui controls for the user interface elements.

## Architecture

The application follows a Model-View-Controller (MVC) pattern:

- **Model**: Game state management including grid, current piece, score, and game rules
- **View**: libui Area component with custom drawing for game visualization
- **Controller**: Event handlers for keyboard input and game timer

### Core Components

1. **TetrisGame Class**: Main game controller managing application lifecycle
2. **GameState Class**: Encapsulates all game state and logic
3. **TetrisPiece Class**: Represents individual Tetris pieces with rotation logic
4. **GameRenderer Class**: Handles all drawing operations using libui Draw API
5. **InputHandler Class**: Processes keyboard events for game controls

## Components and Interfaces

### TetrisGame Class

```php
class TetrisGame {
    private Application $app;
    private WindowWrapper $window;
    private GameState $gameState;
    private GameRenderer $renderer;
    private InputHandler $inputHandler;
    private $area;
    private bool $gameRunning;
    
    public function __construct()
    public function run(): void
    public function stop(): void
    private function setupWindow(): void
    private function setupGameArea(): void
    private function startGameLoop(): void
}
```

### GameState Class

```php
class GameState {
    private array $grid;
    private TetrisPiece $currentPiece;
    private TetrisPiece $nextPiece;
    private int $score;
    private bool $gameOver;
    private float $fallSpeed;
    private float $lastFallTime;
    
    public function __construct()
    public function initializeGame(): void
    public function spawnNewPiece(): void
    public function movePiece(string $direction): bool
    public function rotatePiece(): bool
    public function dropPiece(): void
    public function lockPiece(): void
    public function clearLines(): int
    public function checkGameOver(): bool
    public function getGrid(): array
    public function getCurrentPiece(): TetrisPiece
    public function getNextPiece(): TetrisPiece
    public function getScore(): int
    public function isGameOver(): bool
}
```

### TetrisPiece Class

```php
class TetrisPiece {
    private array $shape;
    private int $colorIndex;
    private int $x;
    private int $y;
    private int $type;
    
    public function __construct(int $type)
    public function getShape(): array
    public function getColorIndex(): int
    public function getPosition(): array
    public function setPosition(int $x, int $y): void
    public function rotate(): array
    public function checkCollision(array $grid, int $newX, int $newY, array $newShape = null): bool
    private function initializeShape(int $type): void
}
```

### GameRenderer Class

```php
class GameRenderer {
    private const BLOCK_SIZE = 30;
    private const GRID_WIDTH = 10;
    private const GRID_HEIGHT = 20;
    private array $colors;
    
    public function __construct()
    public function render($params, GameState $gameState): void
    private function drawBackground($params): void
    private function drawGrid($params): void
    private function drawLockedPieces($params, array $grid): void
    private function drawCurrentPiece($params, TetrisPiece $piece): void
    private function drawGameOverOverlay($params): void
    private function createBrush(array $color): object
    private function drawBlock($params, int $x, int $y, int $colorIndex): void
}
```

### InputHandler Class

```php
class InputHandler {
    private GameState $gameState;
    private $area;
    
    public function __construct(GameState $gameState, $area)
    public function handleKeyPress($keyEvent): bool
    private function processMovement(string $key): void
    private function processRotation(): void
    private function processGameRestart(): void
}
```

## Data Models

### Game Grid
- 2D array representing the 10x20 playing field
- Each cell contains an integer (0 for empty, 1-7 for piece colors)
- Grid coordinates: [row][column] where [0][0] is top-left

### Tetris Pieces
Seven standard Tetris pieces (I, O, T, L, J, S, Z) defined as 2D arrays:

```php
private const SHAPES = [
    1 => [[1, 1, 1, 1]],                    // I-piece
    2 => [[1, 1], [1, 1]],                  // O-piece  
    3 => [[0, 1, 0], [1, 1, 1]],            // T-piece
    4 => [[0, 0, 1], [1, 1, 1]],            // L-piece
    5 => [[1, 0, 0], [1, 1, 1]],            // J-piece
    6 => [[0, 1, 1], [1, 1, 0]],            // S-piece
    7 => [[1, 1, 0], [0, 1, 1]]             // Z-piece
];
```

### Color Scheme
```php
private const COLORS = [
    0 => [0.0, 0.0, 0.0, 0.0],     // Empty (transparent)
    1 => [0.0, 1.0, 1.0, 1.0],     // Cyan (I-piece)
    2 => [1.0, 1.0, 0.0, 1.0],     // Yellow (O-piece)
    3 => [0.5, 0.0, 0.5, 1.0],     // Purple (T-piece)
    4 => [1.0, 0.65, 0.0, 1.0],    // Orange (L-piece)
    5 => [0.0, 0.0, 1.0, 1.0],     // Blue (J-piece)
    6 => [0.0, 1.0, 0.0, 1.0],     // Green (S-piece)
    7 => [1.0, 0.0, 0.0, 1.0]      // Red (Z-piece)
];
```

## Error Handling

### Input Validation
- Validate all movement and rotation operations against grid boundaries
- Prevent invalid piece placements through collision detection
- Handle edge cases for piece spawning at game start and after line clears

### Game State Management
- Graceful handling of game over conditions
- Safe state transitions between playing and game over states
- Proper cleanup of timers and event handlers

### UI Error Handling
- Fallback rendering for invalid game states
- Error recovery for drawing operations
- Safe handling of window close events

## Testing Strategy

### Unit Tests
1. **TetrisPiece Tests**
   - Piece rotation logic
   - Collision detection accuracy
   - Position management

2. **GameState Tests**
   - Line clearing algorithm
   - Score calculation
   - Game over detection
   - Piece spawning logic

3. **GameRenderer Tests**
   - Color mapping correctness
   - Coordinate transformation
   - Drawing boundary validation

### Integration Tests
1. **Game Flow Tests**
   - Complete game session from start to game over
   - Line clearing with score updates
   - Piece movement and locking sequence

2. **Input Handling Tests**
   - WASD key mapping
   - Game restart functionality
   - Input validation during game over state

### Manual Testing
1. **Gameplay Testing**
   - Verify smooth piece movement
   - Test rotation near boundaries
   - Confirm line clearing visual feedback
   - Validate score display accuracy

2. **UI Testing**
   - Window resizing behavior
   - Game over overlay display
   - Next piece preview functionality

## Performance Considerations

### Rendering Optimization
- Minimize drawing operations by only redrawing when game state changes
- Use efficient path creation and brush management
- Implement proper resource cleanup for Draw API objects

### Game Loop Efficiency
- Use appropriate timer intervals for smooth gameplay
- Optimize collision detection algorithms
- Minimize memory allocations during gameplay

### Memory Management
- Proper disposal of libui resources
- Efficient game state updates
- Cleanup of event handlers and timers on application exit