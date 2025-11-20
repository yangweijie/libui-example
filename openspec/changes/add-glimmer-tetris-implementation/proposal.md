## Why
The current Tetris implementation in the LibPHP library lacks the modern UI design and complete feature set shown in the reference "Glimmer Tetris" implementation. To provide a more polished and feature-rich gaming experience, we need to implement a new version that matches the reference UI design and includes a complete menu system, improved scoring, and enhanced visual presentation.

## What Changes
- Create a new Tetris implementation that matches the reference UI design
- Implement a complete menu bar with Game, View, Speed, Options, and Help menus
- Add a proper score panel displaying Score, Lines, and Level
- Implement a centered next piece preview area
- Create a controls information panel
- Enhance visual styling with proper colors and borders for tetrominoes
- Add game over detection with restart functionality
- Implement pause/resume functionality

## Impact
- Affected specs: This will be a new capability specification for the enhanced Tetris game
- Affected code: New files will be created in `src/Components/` and `examples/` directories
- Breaking changes: None, this is an additive enhancement