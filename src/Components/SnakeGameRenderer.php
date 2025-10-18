<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;
use Kingbes\Libui\DrawLineCap;
use Kingbes\Libui\DrawLineJoin;
use Kingbes\Libui\Attribute;
use Kingbes\Libui\TextAlign;
use Kingbes\Libui\TextWeight;
use Kingbes\Libui\TextItalic;
use Kingbes\Libui\TextStretch;

class SnakeGameRenderer {
    /**
     * 渲染游戏
     * @param $params
     * @param SnakeGameState $gameState 游戏状态
     */
    public function render($params, SnakeGameState $gameState): void {
        // 绘制背景
        $this->drawBackground($params, $gameState);
        
        // 绘制网格线
        $this->drawGrid($params, $gameState);
        
        // 绘制蛇
        $this->drawSnake($params, $gameState);
        
        // 绘制食物
        $this->drawFood($params, $gameState);
        
        // 绘制分数
        $this->drawScore($params, $gameState);
        
        // 绘制说明文字
        $this->drawInstructions($params, $gameState);
        
        // 游戏结束提示
        if ($gameState->isGameOver()) {
            $this->drawGameOverOverlay($params, $gameState);
        }
        
        // 暂停提示
        if ($gameState->isPaused() && !$gameState->isGameOver()) {
            $this->drawPauseOverlay($params, $gameState);
        }
    }
    
    /**
     * 绘制背景
     * @param $params
     * @param SnakeGameState $gameState
     */
    private function drawBackground($params, SnakeGameState $gameState): void {
        $bgBrush = Draw::createBrush(DrawBrushType::Solid, 0.1, 0.1, 0.1, 1.0);
        $bgPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle(
            $bgPath,
            0,
            0,
            SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE,
            SnakeGameState::GRID_HEIGHT * SnakeGameState::BLOCK_SIZE
        );
        Draw::pathEnd($bgPath);
        Draw::fill($params, $bgPath, $bgBrush);
    }
    
    /**
     * 绘制网格线
     * @param $params
     * @param SnakeGameState $gameState
     */
    private function drawGrid($params, SnakeGameState $gameState): void {
        $lineBrush = Draw::createBrush(DrawBrushType::Solid, 0.2, 0.2, 0.2, 1.0);
        $linePath = Draw::createPath(DrawFillMode::Winding);

        // 水平线
        for ($y = 0; $y <= SnakeGameState::GRID_HEIGHT; $y++) {
            Draw::createPathFigure($linePath, 0, $y * SnakeGameState::BLOCK_SIZE);
            Draw::pathLineTo($linePath, SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE, $y * SnakeGameState::BLOCK_SIZE);
        }

        // 垂直线
        for ($x = 0; $x <= SnakeGameState::GRID_WIDTH; $x++) {
            Draw::createPathFigure($linePath, $x * SnakeGameState::BLOCK_SIZE, 0);
            Draw::pathLineTo($linePath, $x * SnakeGameState::BLOCK_SIZE, SnakeGameState::GRID_HEIGHT * SnakeGameState::BLOCK_SIZE);
        }

        Draw::pathEnd($linePath);
        $strokeParams = Draw::createStrokeParams(DrawLineCap::Round, DrawLineJoin::Miter, DrawLineJoin::Miter, 1.0);
        Draw::stroke($params, $linePath, $lineBrush, $strokeParams);
    }
    
    /**
     * 绘制蛇
     * @param $params
     * @param SnakeGameState $gameState
     */
    private function drawSnake($params, SnakeGameState $gameState): void {
        $snake = $gameState->getSnake();
        
        foreach ($snake as $index => $segment) {
            // 头部用不同颜色
            if ($index == 0) {
                $brush = Draw::createBrush(DrawBrushType::Solid, 0, 1, 0, 1.0); // 绿色头部
            } else {
                $brush = Draw::createBrush(DrawBrushType::Solid, 0, 0.8, 0, 1.0); // 深绿身体
            }

            $path = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle(
                $path,
                $segment[0] * SnakeGameState::BLOCK_SIZE + 1,
                $segment[1] * SnakeGameState::BLOCK_SIZE + 1,
                SnakeGameState::BLOCK_SIZE - 2,
                SnakeGameState::BLOCK_SIZE - 2
            );
            Draw::pathEnd($path);
            Draw::fill($params, $path, $brush);
        }
    }
    
    /**
     * 绘制食物
     * @param $params
     * @param SnakeGameState $gameState
     */
    private function drawFood($params, SnakeGameState $gameState): void {
        $food = $gameState->getFood();
        
        $foodBrush = Draw::createBrush(DrawBrushType::Solid, 1, 0, 0, 1.0); // 红色食物
        $foodPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle(
            $foodPath,
            $food[0] * SnakeGameState::BLOCK_SIZE + 1,
            $food[1] * SnakeGameState::BLOCK_SIZE + 1,
            SnakeGameState::BLOCK_SIZE - 2,
            SnakeGameState::BLOCK_SIZE - 2
        );
        Draw::pathEnd($foodPath);
        Draw::fill($params, $foodPath, $foodBrush);
    }
    
    /**
     * 绘制分数
     * @param $params
     * @param SnakeGameState $gameState
     */
    private function drawScore($params, SnakeGameState $gameState): void {
        $scoreText = "分数: " . $gameState->getScore();
        $scoreAttr = Attribute::createString($scoreText);
        $scoreColor = Attribute::createColor(1, 0.5, 0.5, 1); // 粉红色
        Attribute::stringSet($scoreAttr, $scoreColor, 0, strlen($scoreText));

        $font = Draw::createFontDesc("宋体", 16.0, TextWeight::Bold, TextItalic::Normal, TextStretch::Normal);
        $scoreLayout = Draw::createTextLayout(Draw::createTextLayoutParams(
            $scoreAttr,
            $font,
            SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE,
            TextAlign::Left
        ));
        Draw::text($params, $scoreLayout, 10, SnakeGameState::GRID_HEIGHT * SnakeGameState::BLOCK_SIZE + 10);
        Draw::freeTextLayout($scoreLayout);
        Attribute::freeString($scoreAttr);
    }
    
    /**
     * 绘制说明文字
     * @param $params
     * @param SnakeGameState $gameState
     */
    private function drawInstructions($params, SnakeGameState $gameState): void {
        $instructions = "操作: 方向键控制  |  空格: 暂停  |  R: 重新开始";
        $instAttr = Attribute::createString($instructions);
        // 说明文字颜色
        $instColor = Attribute::createColor(0, 0, 1, 1); // 蓝色
        Attribute::stringSet($instAttr, $instColor, 0, strlen($instructions));
        
        $font = Draw::createFontDesc("宋体", 16.0, TextWeight::Bold, TextItalic::Normal, TextStretch::Normal);
        $instLayout = Draw::createTextLayout(Draw::createTextLayoutParams(
            $instAttr,
            $font,
            SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE,
            TextAlign::Left
        ));
        Draw::text($params, $instLayout, 10, SnakeGameState::GRID_HEIGHT * SnakeGameState::BLOCK_SIZE + 40);
        Draw::freeTextLayout($instLayout);
        Attribute::freeString($instAttr);
    }
    
    /**
     * 绘制游戏结束覆盖层
     * @param $params
     * @param SnakeGameState $gameState
     */
    private function drawGameOverOverlay($params, SnakeGameState $gameState): void {
        // 半透明背景
        $overBg = Draw::createBrush(DrawBrushType::Solid, 0, 0, 0, 0.7);
        $overBgPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle(
            $overBgPath,
            SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE / 4,
            SnakeGameState::GRID_HEIGHT * SnakeGameState::BLOCK_SIZE / 2 - 40,
            SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE / 2,
            80
        );
        Draw::pathEnd($overBgPath);
        Draw::fill($params, $overBgPath, $overBg);

        // 游戏结束文字
        $overText = "游戏结束! 得分: " . $gameState->getScore();
        $overAttr = Attribute::createString($overText);
        $overWhite = Attribute::createColor(1, 1, 1, 1);
        Attribute::stringSet($overAttr, $overWhite, 0, strlen($overText));

        $overFont = Draw::createFontDesc("宋体", 18.0, TextWeight::Bold, TextItalic::Normal, TextStretch::Normal);
        $overLayout = Draw::createTextLayout(Draw::createTextLayoutParams(
            $overAttr,
            $overFont,
            SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE / 2,
            TextAlign::Center
        ));
        Draw::text(
            $params,
            $overLayout,
            SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE / 4,
            SnakeGameState::GRID_HEIGHT * SnakeGameState::BLOCK_SIZE / 2 - 20
        );
        Draw::freeTextLayout($overLayout);
        Attribute::freeString($overAttr);
    }
    
    /**
     * 绘制暂停覆盖层
     * @param $params
     * @param SnakeGameState $gameState
     */
    private function drawPauseOverlay($params, SnakeGameState $gameState): void {
        $pauseText = "已暂停 (按空格继续)";
        $pauseAttr = Attribute::createString($pauseText);
        $pauseWhite = Attribute::createColor(1, 1, 1, 1);
        Attribute::stringSet($pauseAttr, $pauseWhite, 0, strlen($pauseText));

        $pauseFont = Draw::createFontDesc("宋体", 16.0, TextWeight::Bold, TextItalic::Normal, TextStretch::Normal);
        $pauseLayout = Draw::createTextLayout(Draw::createTextLayoutParams(
            $pauseAttr,
            $pauseFont,
            SnakeGameState::GRID_WIDTH * SnakeGameState::BLOCK_SIZE,
            TextAlign::Center
        ));
        Draw::text(
            $params,
            $pauseLayout,
            0,
            SnakeGameState::GRID_HEIGHT * SnakeGameState::BLOCK_SIZE / 2 - 10
        );
        Draw::freeTextLayout($pauseLayout);
        Attribute::freeString($pauseAttr);
    }
}