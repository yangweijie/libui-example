<?php

namespace LibUI\Components;

use Kingbes\Libui\Menu;
use Kingbes\Libui\MenuItem;
use Yangweijie\Libphp\Components\MessageBox;

class MenuBar {
    private $gameState;
    private $gameArea;
    private $nextPieceArea;
    private $scorePanel;
    
    public function __construct(GameState $gameState, $gameArea, $nextPieceArea, ScorePanel $scorePanel) {
        $this->gameState = $gameState;
        $this->gameArea = $gameArea;
        $this->nextPieceArea = $nextPieceArea;
        $this->scorePanel = $scorePanel;
        
        $this->createMenuBar();
    }
    
    /**
     * Create the menu bar with all menu items
     */
    private function createMenuBar(): void {
        // Create Game menu
        $gameMenu = Menu::create("Game");
        
        // Pause menu item
        $pauseItem = Menu::appendItem($gameMenu, "Pause");
        MenuItem::onClicked($pauseItem, function($item) {
            $this->gameState->setPaused(!$this->gameState->isPaused());
        });
        
        // Restart menu item
        $restartItem = Menu::appendItem($gameMenu, "Restart");
        MenuItem::onClicked($restartItem, function($item) {
            $this->gameState->initializeGame();
            $this->scorePanel->updateAll(0, 0, 1);
            
            // Redraw game areas
            if ($this->gameArea) {
                \Kingbes\Libui\Area::queueRedraw($this->gameArea);
            }
            if ($this->nextPieceArea) {
                \Kingbes\Libui\Area::queueRedraw($this->nextPieceArea);
            }
        });
        
        // Separator
        Menu::appendSeparator($gameMenu);
        
        // Exit menu item
        $exitItem = Menu::appendQuitItem($gameMenu);
        
        // Create View menu
        $viewMenu = Menu::create("View");
        
        // Show next block preview
        $showPreviewItem = Menu::appendItem($viewMenu, "Show Next Block Preview");
        MenuItem::onClicked($showPreviewItem, function($item) {
            $this->gameState->setShowPreview(!$this->gameState->getShowPreview());
            
            // Redraw the next piece area
            if ($this->nextPieceArea) {
                \Kingbes\Libui\Area::queueRedraw($this->nextPieceArea);
            }
        });
        
        // Separator
        Menu::appendSeparator($viewMenu);
        
        // Show high scores
        $highScoresItem = Menu::appendItem($viewMenu, "Show High Scores");
        MenuItem::onClicked($highScoresItem, function($item) {
            // Display high scores dialog
            $highScores = $this->gameState->getHighScores();
            $message = $highScores ? 
                implode("\n", array_map(function($score) {
                    return $score['name'] . " | Score: " . $score['score'] . " | Lines: " . $score['lines'] . " | Level: " . $score['level'];
                }, $highScores)) : 
                "No games have been scored yet.";
                
            $this->showMessageBox("High Scores", $message);
        });
        
        // Clear high scores
        $clearHighScoresItem = Menu::appendItem($viewMenu, "Clear High Scores");
        MenuItem::onClicked($clearHighScoresItem, function($item) {
            $this->gameState->clearHighScores();
        });
        
        // Create Help menu
        $helpMenu = Menu::create("Help");
        
        // About menu item
        $aboutItem = Menu::appendItem($helpMenu, "About");
        MenuItem::onClicked($aboutItem, function($item) {
            $this->showMessageBox("About", "Glimmer Tetris - LibPHP Example - A modern Tetris implementation");
        });
    }
    
    /**
     * Show a message box with the given title and message
     * @param string $title
     * @param string $message
     */
    private function showMessageBox(string $title, string $message): void {
        // We can't show a message box without a window reference
        // For now, we'll just echo the message to stdout
        echo "$title: $message\n";
    }
}

