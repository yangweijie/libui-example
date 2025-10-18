<?php

namespace Yangweijie\Libphp\Components;

class SnakeGameState {
    // 游戏常量
    public const GRID_WIDTH = 20;    // 网格宽度
    public const GRID_HEIGHT = 20;   // 网格高度
    public const BLOCK_SIZE = 25;    // 方块大小(像素)
    public const DIRECTION_UP = 0;
    public const DIRECTION_DOWN = 1;
    public const DIRECTION_LEFT = 2;
    public const DIRECTION_RIGHT = 3;

    // 游戏状态
    private array $snake;
    private int $direction;
    private int $nextDirection;
    private array $food;
    private int $score;
    private bool $gameOver;
    private int $speed;
    private bool $paused;

    public function __construct() {
        $this->initializeGame();
    }

    /**
     * 初始化游戏状态
     */
    public function initializeGame(): void {
        $this->snake = [[10, 10], [9, 10], [8, 10]]; // 蛇身体坐标
        $this->direction = self::DIRECTION_RIGHT;          // 当前方向
        $this->nextDirection = self::DIRECTION_RIGHT;      // 下一次移动方向
        $this->score = 0;                            // 分数
        $this->gameOver = false;                     // 游戏是否结束
        $this->speed = 200;                          // 移动速度(毫秒)
        $this->paused = false;                       // 是否暂停
        $this->spawnFood();
    }

    /**
     * 生成新食物
     */
    public function spawnFood(): void {
        do {
            $x = rand(0, self::GRID_WIDTH - 1);
            $y = rand(0, self::GRID_HEIGHT - 1);
            $onSnake = false;

            // 检查是否生成在蛇身上
            foreach ($this->snake as $segment) {
                if ($segment[0] == $x && $segment[1] == $y) {
                    $onSnake = true;
                    break;
                }
            }
        } while ($onSnake);

        $this->food = [$x, $y];
    }

    /**
     * 移动蛇
     */
    public function moveSnake(): void {
        if ($this->gameOver || $this->paused) return;

        // 更新方向
        $this->direction = $this->nextDirection;

        // 获取头部位置
        $head = $this->snake[0];
        $newHead = $head;

        // 根据方向计算新头部位置
        switch ($this->direction) {
            case self::DIRECTION_UP:
                $newHead[1]--;
                break;
            case self::DIRECTION_DOWN:
                $newHead[1]++;
                break;
            case self::DIRECTION_LEFT:
                $newHead[0]--;
                break;
            case self::DIRECTION_RIGHT:
                $newHead[0]++;
                break;
        }

        // 碰撞检测
        // 边界碰撞
        if (
            $newHead[0] < 0 || $newHead[0] >= self::GRID_WIDTH ||
            $newHead[1] < 0 || $newHead[1] >= self::GRID_HEIGHT
        ) {
            $this->gameOver = true;
            return;
        }

        // 自身碰撞
        foreach ($this->snake as $segment) {
            if ($segment[0] == $newHead[0] && $segment[1] == $newHead[1]) {
                $this->gameOver = true;
                return;
            }
        }

        // 添加新头部
        array_unshift($this->snake, $newHead);

        // 检查是否吃到食物
        if ($newHead[0] == $this->food[0] && $newHead[1] == $this->food[1]) {
            $this->score += 10;
            // 每得50分加快速度
            if ($this->score % 50 == 0 && $this->speed > 100) {
                $this->speed -= 10;
            }
            $this->spawnFood();
        } else {
            // 没吃到食物则移除尾部
            array_pop($this->snake);
        }
    }

    /**
     * 重新开始游戏
     */
    public function restartGame(): void {
        $this->snake = [[10, 10], [9, 10], [8, 10]];
        $this->direction = self::DIRECTION_RIGHT;
        $this->nextDirection = self::DIRECTION_RIGHT;
        $this->score = 0;
        $this->gameOver = false;
        $this->speed = 200;
        $this->paused = false;
        $this->spawnFood();
    }

    /**
     * 设置下一个方向
     * @param int $direction
     */
    public function setNextDirection(int $direction): void {
        // 防止180度转向
        if (($this->direction == self::DIRECTION_UP && $direction != self::DIRECTION_DOWN) ||
            ($this->direction == self::DIRECTION_DOWN && $direction != self::DIRECTION_UP) ||
            ($this->direction == self::DIRECTION_LEFT && $direction != self::DIRECTION_RIGHT) ||
            ($this->direction == self::DIRECTION_RIGHT && $direction != self::DIRECTION_LEFT)) {
            $this->nextDirection = $direction;
        }
    }

    /**
     * 切换暂停状态
     */
    public function togglePause(): void {
        $this->paused = !$this->paused;
    }

    // Getter方法
    public function getSnake(): array {
        return $this->snake;
    }

    public function getDirection(): int {
        return $this->direction;
    }

    public function getFood(): array {
        return $this->food;
    }

    public function getScore(): int {
        return $this->score;
    }

    public function isGameOver(): bool {
        return $this->gameOver;
    }

    public function isPaused(): bool {
        return $this->paused;
    }

    public function getSpeed(): int {
        return $this->speed;
    }
}