<?php

namespace LibUI\Components;

use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;
use Kingbes\Libui\DrawLineCap;
use Kingbes\Libui\DrawLineJoin;

class GameRenderer {
    private const BLOCK_SIZE = 30;
    private const GRID_WIDTH = 10;
    private const GRID_HEIGHT = 20;
    
    // 颜色定义
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
    
    public function __construct() {
    }
    
    /**
     * 渲染游戏
     * @param $params libui绘制参数
     * @param GameState $gameState 游戏状态
     */
    public function render($params, GameState $gameState): void {
        // 绘制背景
        $this->drawBackground($params);
        
        // 绘制网格
        $this->drawGrid($params);
        
        // 绘制已锁定的方块
        $this->drawLockedPieces($params, $gameState->getGrid());
        
        // 绘制当前活动方块
        if ($gameState->getCurrentPiece() !== null && !$gameState->isGameOver()) {
            $this->drawCurrentPiece($params, $gameState->getCurrentPiece());
        }
        
        // 如果游戏结束，绘制游戏结束覆盖层
        if ($gameState->isGameOver()) {
            $this->drawGameOverOverlay($params);
        }
    }
    
    /**
     * 绘制背景
     * @param $params
     */
    private function drawBackground($params): void {
        $backgroundBrush = Draw::createBrush(DrawBrushType::Solid, 0.1, 0.1, 0.1, 1.0);
        $bgPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($bgPath, 0, 0, self::GRID_WIDTH * self::BLOCK_SIZE, self::GRID_HEIGHT * self::BLOCK_SIZE);
        Draw::pathEnd($bgPath);
        Draw::fill($params, $bgPath, $backgroundBrush);
    }
    
    /**
     * 绘制网格线
     * @param $params
     */
    private function drawGrid($params): void {
        $gridBrush = Draw::createBrush(DrawBrushType::Solid, 0.3, 0.3, 0.3, 1.0);
        
        // 绘制垂直线（使用细长矩形模拟线条，避免Stroke方法导致的崩溃）
        for ($i = 0; $i <= self::GRID_WIDTH; $i++) {
            $linePath = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($linePath, $i * self::BLOCK_SIZE, 0, 1, self::GRID_HEIGHT * self::BLOCK_SIZE);
            Draw::pathEnd($linePath);
            Draw::fill($params, $linePath, $gridBrush);
        }
        
        // 绘制水平线（使用细长矩形模拟线条，避免Stroke方法导致的崩溃）
        for ($i = 0; $i <= self::GRID_HEIGHT; $i++) {
            $linePath = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($linePath, 0, $i * self::BLOCK_SIZE, self::GRID_WIDTH * self::BLOCK_SIZE, 1);
            Draw::pathEnd($linePath);
            Draw::fill($params, $linePath, $gridBrush);
        }
    }
    
    /**
     * 绘制已锁定的方块
     * @param $params
     * @param array $grid
     */
    private function drawLockedPieces($params, array $grid): void {
        for ($i = 0; $i < 20; $i++) {
            for ($j = 0; $j < 10; $j++) {
                if ($grid[$i][$j] != 0) {
                    $this->drawBlock($params, $j, $i, $grid[$i][$j]);
                }
            }
        }
    }
    
    /**
     * 绘制当前活动方块
     * @param $params
     * @param TetrisPiece $piece
     */
    private function drawCurrentPiece($params, TetrisPiece $piece): void {
        $pos = $piece->getPosition();
        $shape = $piece->getShape();
        $colorIndex = $piece->getColorIndex();
        
        for ($i = 0; $i < count($shape); $i++) {
            for ($j = 0; $j < count($shape[$i]); $j++) {
                if ($shape[$i][$j] != 0) {
                    $this->drawBlock($params, $pos['x'] + $j, $pos['y'] + $i, $colorIndex);
                }
            }
        }
    }
    
    /**
     * 绘制游戏结束覆盖层
     * @param $params
     */
    private function drawGameOverOverlay($params): void {
        // 半透明黑色覆盖层
        $overlayBrush = Draw::createBrush(DrawBrushType::Solid, 0.0, 0.0, 0.0, 0.7);
        $overlayPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($overlayPath, 0, 0, self::GRID_WIDTH * self::BLOCK_SIZE, self::GRID_HEIGHT * self::BLOCK_SIZE);
        Draw::pathEnd($overlayPath);
        Draw::fill($params, $overlayPath, $overlayBrush);
        
        // 游戏结束文本区域
        $textBackgroundBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 1.0, 1.0, 0.9);
        $textAreaWidth = 200;
        $textAreaHeight = 100;
        $textAreaX = (self::GRID_WIDTH * self::BLOCK_SIZE - $textAreaWidth) / 2;
        $textAreaY = (self::GRID_HEIGHT * self::BLOCK_SIZE - $textAreaHeight) / 2;
        
        $textPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($textPath, $textAreaX, $textAreaY, $textAreaWidth, $textAreaHeight);
        Draw::pathEnd($textPath);
        Draw::fill($params, $textPath, $textBackgroundBrush);
    }
    
    /**
     * 绘制单个方块
     * @param $params
     * @param int $x
     * @param int $y
     * @param int $colorIndex
     */
    private function drawBlock($params, int $x, int $y, int $colorIndex): void {
        // 确保坐标在有效范围内
        if ($x < 0 || $x >= self::GRID_WIDTH || $y < 0 || $y >= self::GRID_HEIGHT) {
            return;
        }
        
        // 获取颜色
        $color = self::COLORS[$colorIndex] ?? self::COLORS[0];
        
        // 创建方块画笔
        $blockBrush = Draw::createBrush(DrawBrushType::Solid, $color[0], $color[1], $color[2], $color[3]);
        
        // 绘制方块（留出边框）
        $blockSize = self::BLOCK_SIZE - 2;
        $path = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($path, $x * self::BLOCK_SIZE + 1, $y * self::BLOCK_SIZE + 1, $blockSize, $blockSize);
        Draw::pathEnd($path);
        Draw::fill($params, $path, $blockBrush);
        
        // 绘制方块边框（使用细长矩形模拟线条，避免Stroke方法导致的崩溃）
        $borderBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 1.0, 1.0, 0.2);
        
        // 上边框
        $topBorder = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($topBorder, $x * self::BLOCK_SIZE + 1, $y * self::BLOCK_SIZE + 1, $blockSize, 1);
        Draw::pathEnd($topBorder);
        Draw::fill($params, $topBorder, $borderBrush);
        
        // 下边框
        $bottomBorder = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($bottomBorder, $x * self::BLOCK_SIZE + 1, $y * self::BLOCK_SIZE + 1 + $blockSize - 1, $blockSize, 1);
        Draw::pathEnd($bottomBorder);
        Draw::fill($params, $bottomBorder, $borderBrush);
        
        // 左边框
        $leftBorder = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($leftBorder, $x * self::BLOCK_SIZE + 1, $y * self::BLOCK_SIZE + 1, 1, $blockSize);
        Draw::pathEnd($leftBorder);
        Draw::fill($params, $leftBorder, $borderBrush);
        
        // 右边框
        $rightBorder = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($rightBorder, $x * self::BLOCK_SIZE + 1 + $blockSize - 1, $y * self::BLOCK_SIZE + 1, 1, $blockSize);
        Draw::pathEnd($rightBorder);
        Draw::fill($params, $rightBorder, $borderBrush);
    }
    
    /**
     * 获取方块大小
     * @return int
     */
    public function getBlockSize(): int {
        return self::BLOCK_SIZE;
    }
}