<?php

namespace LibUI\Components;

use Kingbes\Libui\Box;
use Kingbes\Libui\Label;

class ScorePanel {
    private $container;
    private $scoreLabel;
    private $linesLabel;
    private $levelLabel;
    
    public function __construct() {
        $this->createUI();
    }
    
    /**
     * Create the UI elements for the score panel
     */
    private function createUI(): void {
        // Create container
        $this->container = Box::newVerticalBox();
        Box::setPadded($this->container, true);
        
        // Create score labels
        $this->scoreLabel = Label::create("Score: 0");
        $this->linesLabel = Label::create("Lines: 0");
        $this->levelLabel = Label::create("Level: 1");
        
        // Add labels to container
        Box::append($this->container, $this->scoreLabel, false);
        Box::append($this->container, $this->linesLabel, false);
        Box::append($this->container, $this->levelLabel, false);
    }
    
    /**
     * Get the container control
     * @return mixed
     */
    public function getControl() {
        return $this->container;
    }
    
    /**
     * Update the score display
     * @param int $score
     */
    public function updateScore(int $score): void {
        Label::setText($this->scoreLabel, "Score: " . $score);
    }
    
    /**
     * Update the lines display
     * @param int $lines
     */
    public function updateLines(int $lines): void {
        Label::setText($this->linesLabel, "Lines: " . $lines);
    }
    
    /**
     * Update the level display
     * @param int $level
     */
    public function updateLevel(int $level): void {
        Label::setText($this->levelLabel, "Level: " . $level);
    }
    
    /**
     * Update all displays at once
     * @param int $score
     * @param int $lines
     * @param int $level
     */
    public function updateAll(int $score, int $lines, int $level): void {
        $this->updateScore($score);
        $this->updateLines($lines);
        $this->updateLevel($level);
    }
}