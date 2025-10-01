# Requirements Document

## Introduction

This feature implements a Tetris game using the kingbes/libui library, providing a native GUI-based Tetris experience. The game will be based on the existing raylib implementation but adapted to work with libui's widget-based interface. The game will support WASD key controls and include all standard Tetris gameplay mechanics including piece movement, rotation, line clearing, and scoring.

## Requirements

### Requirement 1

**User Story:** As a player, I want to play Tetris with a native GUI interface, so that I can enjoy the classic puzzle game with familiar desktop application behavior.

#### Acceptance Criteria

1. WHEN the application starts THEN the system SHALL display a native window with the Tetris game interface
2. WHEN the game is running THEN the system SHALL display a 10x20 grid playing field
3. WHEN the game is running THEN the system SHALL show the current score
4. WHEN the game is running THEN the system SHALL display the next piece preview

### Requirement 2

**User Story:** As a player, I want to control Tetris pieces using WASD keys, so that I can move and rotate pieces intuitively.

#### Acceptance Criteria

1. WHEN I press 'A' key THEN the system SHALL move the current piece left by one grid position
2. WHEN I press 'D' key THEN the system SHALL move the current piece right by one grid position  
3. WHEN I press 'S' key THEN the system SHALL move the current piece down by one grid position
4. WHEN I press 'W' key THEN the system SHALL rotate the current piece clockwise by 90 degrees
5. IF a movement would cause collision THEN the system SHALL prevent the movement

### Requirement 3

**User Story:** As a player, I want pieces to automatically fall at regular intervals, so that the game progresses without constant input.

#### Acceptance Criteria

1. WHEN a piece is active THEN the system SHALL automatically move it down every 500 milliseconds
2. WHEN a piece cannot move down further THEN the system SHALL lock it in place on the grid
3. WHEN a piece is locked THEN the system SHALL spawn a new random piece at the top center of the grid
4. IF the new piece cannot be placed THEN the system SHALL trigger game over state

### Requirement 4

**User Story:** As a player, I want completed horizontal lines to be cleared and scored, so that I can progress and achieve higher scores.

#### Acceptance Criteria

1. WHEN a horizontal line is completely filled THEN the system SHALL remove that line
2. WHEN lines are removed THEN the system SHALL move all lines above down to fill the gap
3. WHEN 1 line is cleared THEN the system SHALL add 100 points to the score
4. WHEN 2 lines are cleared simultaneously THEN the system SHALL add 300 points to the score
5. WHEN 3 lines are cleared simultaneously THEN the system SHALL add 500 points to the score
6. WHEN 4 lines are cleared simultaneously THEN the system SHALL add 800 points to the score

### Requirement 5

**User Story:** As a player, I want to see different colored Tetris pieces, so that I can easily distinguish between different piece types.

#### Acceptance Criteria

1. WHEN pieces are displayed THEN the system SHALL use distinct colors for each of the 7 piece types (I, O, T, L, J, S, Z)
2. WHEN pieces are locked on the grid THEN the system SHALL maintain their original colors
3. WHEN the grid is displayed THEN the system SHALL show empty cells with a neutral background

### Requirement 6

**User Story:** As a player, I want to restart the game when it's over, so that I can play multiple rounds without restarting the application.

#### Acceptance Criteria

1. WHEN the game is over THEN the system SHALL display a "Game Over" message
2. WHEN the game is over THEN the system SHALL show restart instructions
3. WHEN I press the space bar during game over THEN the system SHALL reset the game state and start a new game
4. WHEN the game restarts THEN the system SHALL reset the score to zero and clear the grid

### Requirement 7

**User Story:** As a player, I want the game interface to be responsive and well-organized, so that I can focus on gameplay without UI distractions.

#### Acceptance Criteria

1. WHEN the window is displayed THEN the system SHALL show the game grid on the left side
2. WHEN the window is displayed THEN the system SHALL show game information (score, next piece, controls) on the right side
3. WHEN the window is resized THEN the system SHALL maintain proper proportions and readability
4. WHEN pieces are drawn THEN the system SHALL use clear borders and appropriate sizing for visibility