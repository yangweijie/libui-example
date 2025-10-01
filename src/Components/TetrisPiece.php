<?php

namespace LibUI\Components;

class TetrisPiece {
    // 7种标准俄罗斯方块形状定义
    private const SHAPES = [
        1 => [[1, 1, 1, 1]],                    // I-piece
        2 => [[1, 1], [1, 1]],                  // O-piece  
        3 => [[0, 1, 0], [1, 1, 1]],            // T-piece
        4 => [[0, 0, 1], [1, 1, 1]],            // L-piece
        5 => [[1, 0, 0], [1, 1, 1]],            // J-piece
        6 => [[0, 1, 1], [1, 1, 0]],            // S-piece
        7 => [[1, 1, 0], [0, 1, 1]]             // Z-piece
    ];
    
    private array $shape;
    private int $colorIndex;
    private int $x;
    private int $y;
    private int $type;
    
    public function __construct(int $type) {
        $this->type = $type;
        $this->colorIndex = $type;
        $this->initializeShape($type);
        // 初始位置在顶部中央
        $this->x = 4;
        $this->y = 0;
    }
    
    public function getShape(): array {
        return $this->shape;
    }
    
    public function getColorIndex(): int {
        return $this->colorIndex;
    }
    
    public function getPosition(): array {
        return ['x' => $this->x, 'y' => $this->y];
    }
    
    public function setPosition(int $x, int $y): void {
        $this->x = $x;
        $this->y = $y;
    }
    
    public function getType(): int {
        return $this->type;
    }
    
    /**
     * 旋转方块
     * @return array 旋转后的新形状
     */
    public function rotate(): array {
        $rows = count($this->shape);
        $cols = count($this->shape[0]);
        
        // 创建新的旋转后的形状数组
        $newShape = array();
        for ($i = 0; $i < $cols; $i++) {
            $newShape[$i] = array();
            for ($j = 0; $j < $rows; $j++) {
                $newShape[$i][$j] = $this->shape[$rows - 1 - $j][$i];
            }
        }
        
        return $newShape;
    }
    
    /**
     * 检查是否与网格中的其他方块或边界发生碰撞
     * @param array $grid 游戏网格
     * @param int $newX 新的X坐标
     * @param int $newY 新的Y坐标
     * @param array|null $newShape 新的形状（用于旋转检查）
     * @return bool 是否发生碰撞
     */
    public function checkCollision(array $grid, int $newX, int $newY, array $newShape = null): bool {
        $shape = $newShape ?? $this->shape;
        $rows = count($shape);
        $cols = count($shape[0]);
        
        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                // 如果当前形状块不为空
                if ($shape[$i][$j] != 0) {
                    $x = $newX + $j;
                    $y = $newY + $i;
                    
                    // 检查是否超出边界
                    if ($x < 0 || $x >= 10 || $y >= 20) {
                        return true;
                    }
                    
                    // 检查是否与已锁定的方块重叠（y < 0表示方块还在顶部边界外）
                    if ($y >= 0 && $grid[$y][$x] != 0) {
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    /**
     * 初始化方块形状
     * @param int $type 方块类型
     */
    private function initializeShape(int $type): void {
        if (isset(self::SHAPES[$type])) {
            $this->shape = self::SHAPES[$type];
        } else {
            // 默认为I型方块
            $this->shape = self::SHAPES[1];
        }
    }
}