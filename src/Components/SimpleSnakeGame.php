<?php

namespace Yangweijie\Libphp\Components;

use Kingbes\Libui\Draw;
use Kingbes\Libui\DrawBrushType;
use Kingbes\Libui\DrawFillMode;
use FFI\CData;

/**
 * 简化版 Snake 游戏逻辑类
 */
class SimpleSnakeGame
{
    const GRID_SIZE = 20;
    const CELL_SIZE = 20;

    private int $width;
    private int $height;
    private array $snake;
    private array $food;
    private string $direction;
    private bool $gameOver;
    private int $score;

    // 颜色定义
    private array $colors = [
        'snake' => [0.2, 0.8, 0.2, 1.0],     // 绿色蛇身
        'food' => [0.8, 0.2, 0.2, 1.0],      // 红色食物
        'background' => [0.1, 0.1, 0.1, 1.0], // 深灰色背景
        'grid' => [0.3, 0.3, 0.3, 1.0],      // 网格线
        'text' => [1.0, 1.0, 1.0, 1.0]       // 白色文字
    ];

    /**
     * 构造函数
     *
     * @param int $width 游戏区域宽度（单元格数）
     * @param int $height 游戏区域高度（单元格数）
     */
    public function __construct(int $width = 30, int $height = 20)
    {
        $this->width = $width;
        $this->height = $height;
        $this->reset();
    }

    /**
     * 重置游戏
     *
     * @return void
     */
    public function reset(): void
    {
        // 初始化蛇（3节长度，从中间开始）
        $startX = (int)($this->width / 2);
        $startY = (int)($this->height / 2);
        $this->snake = [
            [$startX, $startY],
            [$startX - 1, $startY],
            [$startX - 2, $startY]
        ];

        $this->direction = 'right';
        $this->gameOver = false;
        $this->score = 0;

        // 生成食物
        $this->generateFood();
    }

    /**
     * 生成食物
     *
     * @return void
     */
    private function generateFood(): void
    {
        do {
            $foodX = random_int(0, $this->width - 1);
            $foodY = random_int(0, $this->height - 1);
            $this->food = [$foodX, $foodY];

            // 确保食物不在蛇身上
            $onSnake = false;
            foreach ($this->snake as $segment) {
                if ($segment[0] == $foodX && $segment[1] == $foodY) {
                    $onSnake = true;
                    break;
                }
            }
        } while ($onSnake);
    }

    /**
     * 更新游戏状态
     *
     * @return void
     */
    public function update(): void
    {
        if ($this->gameOver) {
            return;
        }

        // 获取蛇头位置
        $head = $this->snake[0];
        $newHead = [$head[0], $head[1]];

        // 根据方向移动蛇头
        switch ($this->direction) {
            case 'up':
                $newHead[1]--;
                break;
            case 'down':
                $newHead[1]++;
                break;
            case 'left':
                $newHead[0]--;
                break;
            case 'right':
                $newHead[0]++;
                break;
        }

        // 检查碰撞边界
        if ($newHead[0] < 0 || $newHead[0] >= $this->width ||
            $newHead[1] < 0 || $newHead[1] >= $this->height) {
            $this->gameOver = true;
            return;
        }

        // 检查碰撞自己
        foreach ($this->snake as $segment) {
            if ($segment[0] == $newHead[0] && $segment[1] == $newHead[1]) {
                $this->gameOver = true;
                return;
            }
        }

        // 将新头部添加到蛇身
        array_unshift($this->snake, $newHead);

        // 检查是否吃到食物
        if ($newHead[0] == $this->food[0] && $newHead[1] == $this->food[1]) {
            // 吃到食物，增加分数
            $this->score += 10;
            // 生成新食物
            $this->generateFood();
        } else {
            // 没吃到食物，移除尾部
            array_pop($this->snake);
        }
    }

    /**
     * 改变蛇的移动方向
     *
     * @param string $direction 新方向 (up, down, left, right)
     * @return void
     */
    public function changeDirection(string $direction): void
    {
        // 防止反向移动
        if (($this->direction == 'up' && $direction == 'down') ||
            ($this->direction == 'down' && $direction == 'up') ||
            ($this->direction == 'left' && $direction == 'right') ||
            ($this->direction == 'right' && $direction == 'left')) {
            return;
        }

        $this->direction = $direction;
    }

    /**
     * 绘制游戏
     *
     * @param CData $drawParams 绘制参数
     * @return void
     */
    public function draw(CData $drawParams): void
    {
        // 绘制背景
        $this->drawBackground($drawParams);

        // 绘制网格
        $this->drawGrid($drawParams);

        // 绘制食物
        $this->drawFood($drawParams);

        // 绘制蛇
        $this->drawSnake($drawParams);

        // 绘制分数（简化版，只在控制台输出）
        echo "Score: " . $this->score . ($this->gameOver ? " - GAME OVER! Press SPACE to restart" : "") . "\n";
    }

    /**
     * 绘制背景
     *
     * @param CData $drawParams 绘制参数
     * @return void
     */
    private function drawBackground(CData $drawParams): void
    {
        $bgBrush = Draw::createBrush(
            DrawBrushType::Solid,
            $this->colors['background'][0],
            $this->colors['background'][1],
            $this->colors['background'][2],
            $this->colors['background'][3]
        );

        $bgPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle(
            $bgPath,
            0,
            0,
            $this->width * self::CELL_SIZE,
            $this->height * self::CELL_SIZE
        );
        Draw::pathEnd($bgPath);
        Draw::fill($drawParams, $bgPath, \FFI::addr($bgBrush));
    }

    /**
     * 绘制网格
     *
     * @param CData $drawParams 绘制参数
     * @return void
     */
    private function drawGrid(CData $drawParams): void
    {
        $gridBrush = Draw::createBrush(
            DrawBrushType::Solid,
            $this->colors['grid'][0],
            $this->colors['grid'][1],
            $this->colors['grid'][2],
            $this->colors['grid'][3]
        );

        $gridParams = Draw::createStrokeParams();
        Draw::setStrokeWidth($gridParams, 0.5);
        // 初始化其他必要的参数
        $gridParams->Cap = 0; // uiDrawLineCapFlat
        $gridParams->Join = 0; // uiDrawLineJoinMiter
        $gridParams->MiterLimit = 10.0;
        $gridParams->Dashes = null;
        $gridParams->NumDashes = 0;

        // 绘制垂直线
        for ($x = 0; $x <= $this->width; $x++) {
            $path = Draw::createPath(DrawFillMode::Winding);
            Draw::createPathFigure($path, $x * self::CELL_SIZE, 0);
            Draw::pathLineTo($path, $x * self::CELL_SIZE, $this->height * self::CELL_SIZE);
            Draw::pathEnd($path);
            Draw::Stroke($drawParams, $path, \FFI::addr($gridBrush), $gridParams);
        }

        // 绘制水平线
        for ($y = 0; $y <= $this->height; $y++) {
            $path = Draw::createPath(DrawFillMode::Winding);
            Draw::createPathFigure($path, 0, $y * self::CELL_SIZE);
            Draw::pathLineTo($path, $this->width * self::CELL_SIZE, $y * self::CELL_SIZE);
            Draw::pathEnd($path);
            Draw::Stroke($drawParams, $path, \FFI::addr($gridBrush), $gridParams);
        }
    }

    /**
     * 绘制食物
     *
     * @param CData $drawParams 绘制参数
     * @return void
     */
    private function drawFood(CData $drawParams): void
    {
        $foodBrush = Draw::createBrush(
            DrawBrushType::Solid,
            $this->colors['food'][0],
            $this->colors['food'][1],
            $this->colors['food'][2],
            $this->colors['food'][3]
        );

        $foodPath = Draw::createPath(DrawFillMode::Winding);
        Draw::pathAddRectangle(
            $foodPath,
            $this->food[0] * self::CELL_SIZE + 2,
            $this->food[1] * self::CELL_SIZE + 2,
            self::CELL_SIZE - 4,
            self::CELL_SIZE - 4
        );
        Draw::pathEnd($foodPath);
        Draw::fill($drawParams, $foodPath, \FFI::addr($foodBrush));
    }

    /**
     * 绘制蛇
     *
     * @param CData $drawParams 绘制参数
     * @return void
     */
    private function drawSnake(CData $drawParams): void
    {
        $snakeBrush = Draw::createBrush(
            DrawBrushType::Solid,
            $this->colors['snake'][0],
            $this->colors['snake'][1],
            $this->colors['snake'][2],
            $this->colors['snake'][3]
        );

        foreach ($this->snake as $index => $segment) {
            // 蛇头使用不同的颜色
            if ($index == 0) {
                $headBrush = Draw::createBrush(
                    DrawBrushType::Solid,
                    0.0, 1.0, 0.0, 1.0 // 亮绿色蛇头
                );

                $headPath = Draw::createPath(DrawFillMode::Winding);
                Draw::pathAddRectangle(
                    $headPath,
                    $segment[0] * self::CELL_SIZE + 1,
                    $segment[1] * self::CELL_SIZE + 1,
                    self::CELL_SIZE - 2,
                    self::CELL_SIZE - 2
                );
                Draw::pathEnd($headPath);
                Draw::fill($drawParams, $headPath, \FFI::addr($headBrush));
            } else {
                // 蛇身
                $segmentPath = Draw::createPath(DrawFillMode::Winding);
                Draw::pathAddRectangle(
                    $segmentPath,
                    $segment[0] * self::CELL_SIZE + 1,
                    $segment[1] * self::CELL_SIZE + 1,
                    self::CELL_SIZE - 2,
                    self::CELL_SIZE - 2
                );
                Draw::pathEnd($segmentPath);
                Draw::fill($drawParams, $segmentPath, \FFI::addr($snakeBrush));
            }
        }
    }

    /**
     * 获取游戏是否结束
     *
     * @return bool
     */
    public function isGameOver(): bool
    {
        return $this->gameOver;
    }

    /**
     * 获取当前分数
     *
     * @return int
     */
    public function getScore(): int
    {
        return $this->score;
    }

    /**
     * 获取游戏宽度
     *
     * @return int
     */
    public function getWidth(): int
    {
        return $this->width;
    }

    /**
     * 获取游戏高度
     *
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }
}