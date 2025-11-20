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
    
    // Bevel constant for 3D effect
    private const BEVEL_CONSTANT = 20;
    
    // Gray color for grid lines
    private const COLOR_GRAY = [192/255, 192/255, 192/255, 1.0];
    
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
        
        // Calculate bevel pixel size for 3D effect
        $bevelPixelSize = 0.16 * self::BLOCK_SIZE;
        
        // Create main background square
        $backgroundBrush = Draw::createBrush(DrawBrushType::Solid, $color[0], $color[1], $color[2], $color[3]);
        $backgroundPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($backgroundPath, $x * self::BLOCK_SIZE, $y * self::BLOCK_SIZE, self::BLOCK_SIZE, self::BLOCK_SIZE);
        Draw::pathEnd($backgroundPath);
        Draw::fill($params, $backgroundPath, $backgroundBrush);
        Draw::freePath($backgroundPath);
        
        // Create top bevel
        $topBevelColor = [
            min(1.0, $color[0] + 4 * (self::BEVEL_CONSTANT / 255)),
            min(1.0, $color[1] + 4 * (self::BEVEL_CONSTANT / 255)),
            min(1.0, $color[2] + 4 * (self::BEVEL_CONSTANT / 255)),
            $color[3]
        ];
        $topBevelBrush = Draw::createBrush(DrawBrushType::Solid, $topBevelColor[0], $topBevelColor[1], $topBevelColor[2], $topBevelColor[3]);
        $topBevelPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($topBevelPath, $x * self::BLOCK_SIZE, $y * self::BLOCK_SIZE, self::BLOCK_SIZE - $bevelPixelSize, $bevelPixelSize);
        Draw::pathEnd($topBevelPath);
        Draw::fill($params, $topBevelPath, $topBevelBrush);
        Draw::freePath($topBevelPath);
        
        // Create right bevel
        $rightBevelColor = [
            max(0.0, $color[0] - (self::BEVEL_CONSTANT / 255)),
            max(0.0, $color[1] - (self::BEVEL_CONSTANT / 255)),
            max(0.0, $color[2] - (self::BEVEL_CONSTANT / 255)),
            $color[3]
        ];
        $rightBevelBrush = Draw::createBrush(DrawBrushType::Solid, $rightBevelColor[0], $rightBevelColor[1], $rightBevelColor[2], $rightBevelColor[3]);
        $rightBevelPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($rightBevelPath, $x * self::BLOCK_SIZE + self::BLOCK_SIZE - $bevelPixelSize, $y * self::BLOCK_SIZE, $bevelPixelSize, self::BLOCK_SIZE - $bevelPixelSize);
        Draw::pathEnd($rightBevelPath);
        Draw::fill($params, $rightBevelPath, $rightBevelBrush);
        Draw::freePath($rightBevelPath);
        
        // Create bottom bevel
        $bottomBevelColor = [
            max(0.0, $color[0] - (self::BEVEL_CONSTANT / 255)),
            max(0.0, $color[1] - (self::BEVEL_CONSTANT / 255)),
            max(0.0, $color[2] - (self::BEVEL_CONSTANT / 255)),
            $color[3]
        ];
        $bottomBevelBrush = Draw::createBrush(DrawBrushType::Solid, $bottomBevelColor[0], $bottomBevelColor[1], $bottomBevelColor[2], $bottomBevelColor[3]);
        $bottomBevelPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($bottomBevelPath, $x * self::BLOCK_SIZE + $bevelPixelSize, $y * self::BLOCK_SIZE + self::BLOCK_SIZE - $bevelPixelSize, self::BLOCK_SIZE - $bevelPixelSize, $bevelPixelSize);
        Draw::pathEnd($bottomBevelPath);
        Draw::fill($params, $bottomBevelPath, $bottomBevelBrush);
        Draw::freePath($bottomBevelPath);
        
        // Create left bevel
        $leftBevelColor = [
            max(0.0, $color[0] - (self::BEVEL_CONSTANT / 255)),
            max(0.0, $color[1] - (self::BEVEL_CONSTANT / 255)),
            max(0.0, $color[2] - (self::BEVEL_CONSTANT / 255)),
            $color[3]
        ];
        $leftBevelBrush = Draw::createBrush(DrawBrushType::Solid, $leftBevelColor[0], $leftBevelColor[1], $leftBevelColor[2], $leftBevelColor[3]);
        $leftBevelPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($leftBevelPath, $x * self::BLOCK_SIZE, $y * self::BLOCK_SIZE + $bevelPixelSize, $bevelPixelSize, self::BLOCK_SIZE - $bevelPixelSize);
        Draw::pathEnd($leftBevelPath);
        Draw::fill($params, $leftBevelPath, $leftBevelBrush);
        Draw::freePath($leftBevelPath);
        
        // Create border square
        $borderColor = $colorIndex == 0 ? self::COLOR_GRAY : $color;
        $borderBrush = Draw::createBrush(DrawBrushType::Solid, $borderColor[0], $borderColor[1], $borderColor[2], $borderColor[3]);
        $borderPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($borderPath, $x * self::BLOCK_SIZE, $y * self::BLOCK_SIZE, self::BLOCK_SIZE, self::BLOCK_SIZE);
        Draw::pathEnd($borderPath);
        $strokeParams = Draw::createStrokeParams(
            \Kingbes\Libui\DrawLineCap::Flat,
            \Kingbes\Libui\DrawLineJoin::Miter,
            \Kingbes\Libui\DrawLineJoin::Miter,
            1.0,   // 线宽
            10.0,  // miterLimit
            0,     // numDashes
            0.0    // DashPhase
        );
        Draw::Stroke($params, $borderPath, $borderBrush, $strokeParams);
        Draw::freePath($borderPath);
    }
    
    /**
     * 获取方块大小
     * @return int
     */
    public function getBlockSize(): int {
        return self::BLOCK_SIZE;
    }
}