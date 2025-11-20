<?php

namespace LibUI\Components;

class GameState {
    private array $grid;
    private ?TetrisPiece $currentPiece;
    private ?TetrisPiece $nextPiece;
    private int $score;
    private int $lines;
    private int $level;
    private bool $gameOver;
    private bool $paused;
    private bool $showPreview;
    private string $upArrowAction;
    private float $fallSpeed;
    private float $lastFallTime;
    private array $highScores;
    
    public function __construct() {
        $this->initializeGame();
    }
    
    /**
     * 初始化游戏状态
     */
    public function initializeGame(): void {
        // 初始化20x10的游戏网格
        $this->grid = array();
        for ($i = 0; $i < 20; $i++) {
            $this->grid[$i] = array_fill(0, 10, 0);
        }
        
        $this->score = 0;
        $this->lines = 0;
        $this->level = 1;
        $this->gameOver = false;
        $this->paused = false;
        $this->showPreview = true;
        $this->upArrowAction = 'rotate_right'; // Default action for up arrow
        $this->fallSpeed = 0.5; // 500毫秒下落一次
        $this->lastFallTime = microtime(true);
        $this->highScores = [];
        
        // 生成下一个方块
        $this->nextPiece = new TetrisPiece(rand(1, 7));
        
        // 生成当前方块
        $this->spawnNewPiece();
    }
    
    /**
     * 生成新方块
     */
    public function spawnNewPiece(): void {
        $this->currentPiece = $this->nextPiece;
        $this->nextPiece = new TetrisPiece(rand(1, 7));
        
        // 检查新方块是否能放置（游戏是否结束）
        if ($this->currentPiece->checkCollision($this->grid, 
                $this->currentPiece->getPosition()['x'], 
                $this->currentPiece->getPosition()['y'])) {
            $this->gameOver = true;
            $this->saveHighScore();
        }
    }
    
    /**
     * 移动当前方块
     * @param string $direction 移动方向 ('left', 'right', 'down')
     * @return bool 移动是否成功
     */
    public function movePiece(string $direction): bool {
        if ($this->gameOver || $this->currentPiece === null || $this->paused) {
            return false;
        }
        
        $pos = $this->currentPiece->getPosition();
        $newX = $pos['x'];
        $newY = $pos['y'];
        
        switch ($direction) {
            case 'left':
                $newX--;
                break;
            case 'right':
                $newX++;
                break;
            case 'down':
                $newY++;
                break;
            default:
                return false;
        }
        
        // 检查是否可以移动到新位置
        if (!$this->currentPiece->checkCollision($this->grid, $newX, $newY)) {
            $this->currentPiece->setPosition($newX, $newY);
            return true;
        }
        
        // 如果是向下移动且发生碰撞，则锁定方块
        if ($direction === 'down') {
            $this->lockPiece();
            return false;
        }
        
        return false;
    }
    
    /**
     * 旋转当前方块
     * @return bool 旋转是否成功
     */
    public function rotatePiece(): bool {
        if ($this->gameOver || $this->currentPiece === null || $this->paused) {
            return false;
        }
        
        $newShape = $this->currentPiece->rotate();
        $pos = $this->currentPiece->getPosition();
        
        // 检查旋转后是否发生碰撞
        if (!$this->currentPiece->checkCollision($this->grid, $pos['x'], $pos['y'], $newShape)) {
            // 更新方块形状
            $reflection = new \ReflectionClass($this->currentPiece);
            $property = $reflection->getProperty('shape');
            $property->setAccessible(true);
            $property->setValue($this->currentPiece, $newShape);
            return true;
        }
        
        return false;
    }
    
    /**
     * 向左旋转当前方块
     * @return bool 旋转是否成功
     */
    public function rotatePieceLeft(): bool {
        if ($this->gameOver || $this->currentPiece === null || $this->paused) {
            return false;
        }
        
        $newShape = $this->currentPiece->rotateLeft();
        $pos = $this->currentPiece->getPosition();
        
        // 检查旋转后是否发生碰撞
        if (!$this->currentPiece->checkCollision($this->grid, $pos['x'], $pos['y'], $newShape)) {
            // 更新方块形状
            $reflection = new \ReflectionClass($this->currentPiece);
            $property = $reflection->getProperty('shape');
            $property->setAccessible(true);
            $property->setValue($this->currentPiece, $newShape);
            return true;
        }
        
        return false;
    }
    
    /**
     * 立即下落方块
     */
    public function dropPiece(): void {
        if ($this->gameOver || $this->currentPiece === null || $this->paused) {
            return;
        }
        
        // 持续下落直到碰撞
        while ($this->movePiece('down')) {
            // 继续下落
        }
    }
    
    /**
     * 锁定当前方块到网格
     */
    public function lockPiece(): void {
        if ($this->currentPiece === null) {
            return;
        }
        
        $pos = $this->currentPiece->getPosition();
        $shape = $this->currentPiece->getShape();
        $colorIndex = $this->currentPiece->getColorIndex();
        
        // 将方块锁定到网格
        for ($i = 0; $i < count($shape); $i++) {
            for ($j = 0; $j < count($shape[$i]); $j++) {
                if ($shape[$i][$j] != 0) {
                    $x = $pos['x'] + $j;
                    $y = $pos['y'] + $i;
                    
                    // 确保坐标在有效范围内
                    if ($y >= 0 && $y < 20 && $x >= 0 && $x < 10) {
                        $this->grid[$y][$x] = $colorIndex;
                    }
                }
            }
        }
        
        // 清除完整行并更新分数
        $linesCleared = $this->clearLines();
        $this->updateScore($linesCleared);
        
        // 生成新方块
        $this->spawnNewPiece();
    }
    
    /**
     * 清除完整行
     * @return int 清除的行数
     */
    public function clearLines(): int {
        $linesCleared = 0;
        
        // 从底部开始检查每一行
        for ($i = 19; $i >= 0; $i--) {
            $isLineComplete = true;
            
            // 检查当前行是否完整
            for ($j = 0; $j < 10; $j++) {
                if ($this->grid[$i][$j] == 0) {
                    $isLineComplete = false;
                    break;
                }
            }
            
            // 如果行完整，则清除它
            if ($isLineComplete) {
                // 将上方所有行下移
                for ($k = $i; $k > 0; $k--) {
                    for ($j = 0; $j < 10; $j++) {
                        $this->grid[$k][$j] = $this->grid[$k-1][$j];
                    }
                }
                
                // 清空顶部行
                for ($j = 0; $j < 10; $j++) {
                    $this->grid[0][$j] = 0;
                }
                
                // 增加清除行数并继续检查当前行（因为下移了新行）
                $linesCleared++;
                $i++;
            }
        }
        
        return $linesCleared;
    }
    
    /**
     * 更新分数
     * @param int $linesCleared 清除的行数
     */
    private function updateScore(int $linesCleared): void {
        // Update lines count
        $this->lines += $linesCleared;
        
        // Update score based on lines cleared
        switch ($linesCleared) {
            case 1:
                $this->score += 100 * $this->level;
                break;
            case 2:
                $this->score += 300 * $this->level;
                break;
            case 3:
                $this->score += 500 * $this->level;
                break;
            case 4:
                $this->score += 800 * $this->level;
                break;
        }
        
        // Update level every 10 lines
        $newLevel = intval($this->lines / 10) + 1;
        if ($newLevel > $this->level) {
            $this->level = $newLevel;
            // Increase fall speed with level
            $this->fallSpeed = max(0.05, 0.5 - ($this->level - 1) * 0.05);
        }
    }
    
    /**
     * 保存高分记录
     */
    private function saveHighScore(): void {
        $highScore = [
            'name' => 'Player',
            'score' => $this->score,
            'lines' => $this->lines,
            'level' => $this->level,
            'date' => date('Y-m-d H:i:s')
        ];
        
        $this->highScores[] = $highScore;
        
        // Keep only top 10 scores
        usort($this->highScores, function($a, $b) {
            return $b['score'] - $a['score'];
        });
        
        $this->highScores = array_slice($this->highScores, 0, 10);
    }
    
    /**
     * 清除高分记录
     */
    public function clearHighScores(): void {
        $this->highScores = [];
    }
    
    // Getter and Setter methods
    public function getGrid(): array {
        return $this->grid;
    }
    
    public function getCurrentPiece(): ?TetrisPiece {
        return $this->currentPiece;
    }
    
    public function getNextPiece(): ?TetrisPiece {
        return $this->nextPiece;
    }
    
    public function getScore(): int {
        return $this->score;
    }
    
    public function getLines(): int {
        return $this->lines;
    }
    
    public function getLevel(): int {
        return $this->level;
    }
    
    public function isGameOver(): bool {
        return $this->gameOver;
    }
    
    public function isPaused(): bool {
        return $this->paused;
    }
    
    public function setPaused(bool $paused): void {
        $this->paused = $paused;
    }
    
    public function getShowPreview(): bool {
        return $this->showPreview;
    }
    
    public function setShowPreview(bool $showPreview): void {
        $this->showPreview = $showPreview;
    }
    
    public function getUpArrowAction(): string {
        return $this->upArrowAction;
    }
    
    public function setUpArrowAction(string $action): void {
        $this->upArrowAction = $action;
    }
    
    public function getFallSpeed(): float {
        return $this->fallSpeed;
    }
    
    public function getLastFallTime(): float {
        return $this->lastFallTime;
    }
    
    public function setLastFallTime(float $time): void {
        $this->lastFallTime = $time;
    }
    
    public function getHighScores(): array {
        return $this->highScores;
    }
}