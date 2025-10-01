<?php

namespace LibUI\Components;

class GameState {
    private array $grid;
    private ?TetrisPiece $currentPiece;
    private ?TetrisPiece $nextPiece;
    private int $score;
    private bool $gameOver;
    private float $fallSpeed;
    private float $lastFallTime;
    
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
        $this->gameOver = false;
        $this->fallSpeed = 0.5; // 500毫秒下落一次
        $this->lastFallTime = microtime(true);
        
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
        }
    }
    
    /**
     * 移动当前方块
     * @param string $direction 移动方向 ('left', 'right', 'down')
     * @return bool 移动是否成功
     */
    public function movePiece(string $direction): bool {
        if ($this->gameOver || $this->currentPiece === null) {
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
        if ($this->gameOver || $this->currentPiece === null) {
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
     * 立即下落方块
     */
    public function dropPiece(): void {
        if ($this->gameOver || $this->currentPiece === null) {
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
        switch ($linesCleared) {
            case 1:
                $this->score += 100;
                break;
            case 2:
                $this->score += 300;
                break;
            case 3:
                $this->score += 500;
                break;
            case 4:
                $this->score += 800;
                break;
        }
    }
    
    /**
     * 检查游戏是否结束
     * @return bool 游戏是否结束
     */
    public function checkGameOver(): bool {
        return $this->gameOver;
    }
    
    // Getter方法
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
    
    public function isGameOver(): bool {
        return $this->gameOver;
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
}