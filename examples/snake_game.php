<?php

require dirname(__DIR__) . "/vendor/autoload.php";

use Yangweijie\Libphp\Components\Application;
use Yangweijie\Libphp\Components\Area;
use Yangweijie\Libphp\Components\SnakeGame;
use Kingbes\Libui\Control;
use Kingbes\Libui\App;

// 创建应用
$app = new Application();

// 创建窗口
$window = $app->createWindow("Snake Game", 800, 600);

// 创建 Snake 游戏实例
$snakeGame = new SnakeGame(30, 20); // 30x20 的游戏区域

// 创建 Area 组件
$area = new Area();

// 设置绘制回调
$area->onDraw(function ($drawParams) use ($snakeGame) {
    $snakeGame->draw($drawParams);
});

// 设置键盘事件回调
$area->onKey(function ($keyEvent) use ($snakeGame, $area) {
    // 处理方向键
    switch ($keyEvent->Key) {
        case 38: // 上箭头
            $snakeGame->changeDirection('up');
            break;
        case 40: // 下箭头
            $snakeGame->changeDirection('down');
            break;
        case 37: // 左箭头
            $snakeGame->changeDirection('left');
            break;
        case 39: // 右箭头
            $snakeGame->changeDirection('right');
            break;
        case 32: // 空格键重新开始游戏
            if ($snakeGame->isGameOver()) {
                $snakeGame->reset();
            }
            break;
    }

    // 触发重绘
    $area->queueRedraw();

    // 返回 true 表示已处理该事件
    return 1;
});

// 游戏循环定时器
$lastUpdate = microtime(true);
$gameSpeed = 0.1; // 每秒10帧

// 设置窗口内容
$window->setContent($area->getControl());

// 设置窗口关闭事件
$window->onClose(function ($window) use ($app) {
    echo "游戏窗口关闭\n";
    $app->quit();
    return true;
});

// 显示窗口
$window->show();

// 启动游戏循环
App::onShouldQuit(function () use ($app) {
    $app->quit();
    return true;
});

// 使用一个简单的游戏循环
$app->run();