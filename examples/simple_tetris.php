<?php

require __DIR__ . "/../vendor/autoload.php";

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Area;
use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;

// 定义游戏常量
define('GRID_WIDTH', 10);
define('GRID_HEIGHT', 20);
define('BLOCK_SIZE', 30);

// 简单的游戏状态
$gameState = [
    'grid' => array_fill(0, GRID_HEIGHT, array_fill(0, GRID_WIDTH, 0)),
    'currentPiece' => [
        'shape' => [[1, 1, 1, 1]], // I型方块
        'x' => 4,
        'y' => 0,
        'color' => 1
    ],
    'score' => 0
];

// 颜色定义
$colors = [
    0 => [0.0, 0.0, 0.0, 0.0],     // Empty (transparent)
    1 => [0.0, 1.0, 1.0, 1.0],     // Cyan (I-piece)
];

// 初始化应用
App::init();

// 创建窗口
$window = Window::create("简化俄罗斯方块", GRID_WIDTH * BLOCK_SIZE + 150, GRID_HEIGHT * BLOCK_SIZE + 50, 0);
Window::setMargined($window, true);

// 窗口关闭事件
Window::onClosing($window, function ($window) {
    App::quit();
    return 1;
});

// 创建主水平布局
$mainLayout = Box::newHorizontalBox();
Box::setPadded($mainLayout, true);
Window::setChild($window, $mainLayout);

// 创建垂直布局用于游戏区域
$gameLayout = Box::newVerticalBox();
Box::setPadded($gameLayout, true);
Box::append($mainLayout, $gameLayout, 1);

// 创建标签显示"Game"
$gameLabel = Label::create("Game");
Box::append($gameLayout, $gameLabel, 0);

// 创建游戏区域处理程序
$areaHandler = Area::handler(
    function ($handler, $area, $params) use ($gameState, $colors) { // 绘制回调
        // 绘制背景
        $backgroundBrush = Draw::createBrush(DrawBrushType::Solid, 0.1, 0.1, 0.1, 1.0);
        $bgPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle($bgPath, 0, 0, GRID_WIDTH * BLOCK_SIZE, GRID_HEIGHT * BLOCK_SIZE);
        Draw::pathEnd($bgPath);
        Draw::fill($params, $bgPath, $backgroundBrush);
        
        // 绘制网格线（使用fill方法避免Stroke导致的崩溃）
        $gridBrush = Draw::createBrush(DrawBrushType::Solid, 0.3, 0.3, 0.3, 1.0);
        
        // 绘制垂直线
        for ($i = 0; $i <= GRID_WIDTH; $i++) {
            $linePath = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($linePath, $i * BLOCK_SIZE, 0, 1, GRID_HEIGHT * BLOCK_SIZE);
            Draw::pathEnd($linePath);
            Draw::fill($params, $linePath, $gridBrush);
        }
        
        // 绘制水平线
        for ($i = 0; $i <= GRID_HEIGHT; $i++) {
            $linePath = Draw::createPath(DrawFillMode::Winding);
            Draw::pathAddRectangle($linePath, 0, $i * BLOCK_SIZE, GRID_WIDTH * BLOCK_SIZE, 1);
            Draw::pathEnd($linePath);
            Draw::fill($params, $linePath, $gridBrush);
        }
        
        // 绘制已锁定的方块
        for ($y = 0; $y < GRID_HEIGHT; $y++) {
            for ($x = 0; $x < GRID_WIDTH; $x++) {
                if ($gameState['grid'][$y][$x] != 0) {
                    $colorIndex = $gameState['grid'][$y][$x];
                    $color = $colors[$colorIndex];
                    $blockBrush = Draw::createBrush(DrawBrushType::Solid, $color[0], $color[1], $color[2], $color[3]);
                    $path = Draw::createPath(DrawFillMode::Winding);
                    Draw::pathAddRectangle($path, $x * BLOCK_SIZE + 1, $y * BLOCK_SIZE + 1, BLOCK_SIZE - 2, BLOCK_SIZE - 2);
                    Draw::pathEnd($path);
                    Draw::fill($params, $path, $blockBrush);
                }
            }
        }
        
        // 绘制当前方块
        $piece = $gameState['currentPiece'];
        $shape = $piece['shape'];
        $color = $colors[$piece['color']];
        $blockBrush = Draw::createBrush(DrawBrushType::Solid, $color[0], $color[1], $color[2], $color[3]);
        
        for ($i = 0; $i < count($shape); $i++) {
            for ($j = 0; $j < count($shape[$i]); $j++) {
                if ($shape[$i][$j] != 0) {
                    $x = $piece['x'] + $j;
                    $y = $piece['y'] + $i;
                    $path = Draw::createPath(DrawFillMode::Winding);
                    Draw::pathAddRectangle($path, $x * BLOCK_SIZE + 1, $y * BLOCK_SIZE + 1, BLOCK_SIZE - 2, BLOCK_SIZE - 2);
                    Draw::pathEnd($path);
                    Draw::fill($params, $path, $blockBrush);
                }
            }
        }
    },
    function ($handler, $area, $keyEvent) use (&$gameState) { // 按键回调
        switch ($keyEvent->Key) {
            case 'a':
            case 'A':
                $gameState['currentPiece']['x']--;
                // 简单的边界检查
                if ($gameState['currentPiece']['x'] < 0) {
                    $gameState['currentPiece']['x'] = 0;
                }
                Area::queueRedraw($area);
                break;
            case 'd':
            case 'D':
                $gameState['currentPiece']['x']++;
                // 简单的边界检查
                if ($gameState['currentPiece']['x'] > GRID_WIDTH - count($gameState['currentPiece']['shape'][0])) {
                    $gameState['currentPiece']['x'] = GRID_WIDTH - count($gameState['currentPiece']['shape'][0]);
                }
                Area::queueRedraw($area);
                break;
            case 's':
            case 'S':
                $gameState['currentPiece']['y']++;
                // 简单的边界检查
                if ($gameState['currentPiece']['y'] > GRID_HEIGHT - count($gameState['currentPiece']['shape'])) {
                    $gameState['currentPiece']['y'] = GRID_HEIGHT - count($gameState['currentPiece']['shape']);
                }
                Area::queueRedraw($area);
                break;
        }
        return 1;
    }
);

// 创建游戏区域
$gameArea = Area::create($areaHandler);
Box::append($gameLayout, $gameArea, 0);

// 创建垂直布局用于信息显示
$infoLayout = Box::newVerticalBox();
Box::setPadded($infoLayout, true);
Box::append($mainLayout, $infoLayout, 0);

// 创建分数标签
$scoreLabel = Label::create("Score: 0");
Box::append($infoLayout, $scoreLabel, 0);

// 创建控制说明标签
$controlsLabel = Label::create("Controls:\nA - Move Left\nD - Move Right\nS - Move Down");
Box::append($infoLayout, $controlsLabel, 0);

// 显示窗口
Control::show($window);

// 主循环
App::main();