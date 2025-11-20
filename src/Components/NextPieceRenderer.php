<?php

namespace LibUI\Components;

use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;
use Kingbes\Libui\DrawLineCap;
use Kingbes\Libui\DrawLineJoin;

class NextPieceRenderer {
    private const BLOCK_SIZE = 20;
    private const BEVEL_CONSTANT = 20;
    
    // Gray color for grid lines
    private const COLOR_GRAY = [192/255, 192/255, 192/255, 1.0];
    
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
        
        // Calculate bevel pixel size for 3D effect
        $bevelPixelSize = 0.16 * self::BLOCK_SIZE;
        
        // Create main background square
        $backgroundBrush = Draw::createBrush(DrawBrushType::Solid, $color[0], $color[1], $color[2], $color[3]);
        $backgroundPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($backgroundPath, $x, $y, self::BLOCK_SIZE, self::BLOCK_SIZE);
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
        Draw::pathAddRectangle($topBevelPath, $x, $y, self::BLOCK_SIZE - $bevelPixelSize, $bevelPixelSize);
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
        Draw::pathAddRectangle($rightBevelPath, $x + self::BLOCK_SIZE - $bevelPixelSize, $y, $bevelPixelSize, self::BLOCK_SIZE - $bevelPixelSize);
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
        Draw::pathAddRectangle($bottomBevelPath, $x + $bevelPixelSize, $y + self::BLOCK_SIZE - $bevelPixelSize, self::BLOCK_SIZE - $bevelPixelSize, $bevelPixelSize);
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
        Draw::pathAddRectangle($leftBevelPath, $x, $y + $bevelPixelSize, $bevelPixelSize, self::BLOCK_SIZE - $bevelPixelSize);
        Draw::pathEnd($leftBevelPath);
        Draw::fill($params, $leftBevelPath, $leftBevelBrush);
        Draw::freePath($leftBevelPath);
        
        // Create border square
        $borderColor = $colorIndex == 0 ? self::COLOR_GRAY : $color;
        $borderBrush = Draw::createBrush(DrawBrushType::Solid, $borderColor[0], $borderColor[1], $borderColor[2], $borderColor[3]);
        $borderPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($borderPath, $x, $y, self::BLOCK_SIZE, self::BLOCK_SIZE);
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
}