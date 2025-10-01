<?php

namespace LibUI\Components;

use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;
use Kingbes\Libui\DrawLineCap;
use Kingbes\Libui\DrawLineJoin;

class NextPieceRenderer {
    private const BLOCK_SIZE = 20;
    
    // 颜色定义（与主渲染器相同）
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
     * 渲染下一个方块
     * @param $params libui绘制参数
     * @param TetrisPiece|null $nextPiece 下一个方块
     */
    public function render($params, ?TetrisPiece $nextPiece): void {
        if ($nextPiece === null) {
            return;
        }
        
        // 绘制背景
        $this->drawBackground($params);
        
        // 绘制下一个方块
        $this->drawNextPiece($params, $nextPiece);
    }
    
    /**
     * 绘制背景
     * @param $params
     */
    private function drawBackground($params): void {
        $backgroundBrush = Draw::createBrush(DrawBrushType::Solid, 0.1, 0.1, 0.1, 1.0);
        $bgPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($bgPath, 0, 0, 120, 120);
        Draw::pathEnd($bgPath);
        Draw::fill($params, $bgPath, $backgroundBrush);
    }
    
    /**
     * 绘制下一个方块
     * @param $params
     * @param TetrisPiece $piece
     */
    private function drawNextPiece($params, TetrisPiece $piece): void {
        $shape = $piece->getShape();
        $colorIndex = $piece->getColorIndex();
        
        // 计算居中位置
        $rows = count($shape);
        $cols = count($shape[0]);
        $offsetX = (120 - ($cols * self::BLOCK_SIZE)) / 2;
        $offsetY = (120 - ($rows * self::BLOCK_SIZE)) / 2;
        
        for ($i = 0; $i < $rows; $i++) {
            for ($j = 0; $j < $cols; $j++) {
                if ($shape[$i][$j] != 0) {
                    $this->drawBlock($params, $offsetX + ($j * self::BLOCK_SIZE), $offsetY + ($i * self::BLOCK_SIZE), $colorIndex);
                }
            }
        }
    }
    
    /**
     * 绘制单个方块
     * @param $params
     * @param int $x
     * @param int $y
     * @param int $colorIndex
     */
    private function drawBlock($params, int $x, int $y, int $colorIndex): void {
        // 获取颜色
        $color = self::COLORS[$colorIndex] ?? self::COLORS[0];
        
        // 创建方块画笔
        $blockBrush = Draw::createBrush(DrawBrushType::Solid, $color[0], $color[1], $color[2], $color[3]);
        
        // 绘制方块（留出边框）
        $blockSize = self::BLOCK_SIZE - 2;
        $path = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($path, $x + 1, $y + 1, $blockSize, $blockSize);
        Draw::pathEnd($path);
        Draw::fill($params, $path, $blockBrush);
        
        // 绘制方块边框（使用细长矩形模拟线条，避免Stroke方法导致的崩溃）
        $borderBrush = Draw::createBrush(DrawBrushType::Solid, 1.0, 1.0, 1.0, 0.2);
        
        // 上边框
        $topBorder = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($topBorder, $x + 1, $y + 1, $blockSize, 1);
        Draw::pathEnd($topBorder);
        Draw::fill($params, $topBorder, $borderBrush);
        
        // 下边框
        $bottomBorder = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($bottomBorder, $x + 1, $y + 1 + $blockSize - 1, $blockSize, 1);
        Draw::pathEnd($bottomBorder);
        Draw::fill($params, $bottomBorder, $borderBrush);
        
        // 左边框
        $leftBorder = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($leftBorder, $x + 1, $y + 1, 1, $blockSize);
        Draw::pathEnd($leftBorder);
        Draw::fill($params, $leftBorder, $borderBrush);
        
        // 右边框
        $rightBorder = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($rightBorder, $x + 1 + $blockSize - 1, $y + 1, 1, $blockSize);
        Draw::pathEnd($rightBorder);
        Draw::fill($params, $rightBorder, $borderBrush);
    }
}