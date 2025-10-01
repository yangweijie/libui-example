<?php

/**
 * 俄罗斯方块游戏 - 基于libui的实现
 * 
 * 控制说明:
 * A - 向左移动
 * D - 向右移动
 * S - 向下移动
 * W - 旋转方块
 * 空格 - 立即下落/游戏结束后重新开始
 */

// 自动加载类
require_once __DIR__ . '/../vendor/autoload.php';

use LibUI\Components\TetrisGame;

try {
    // 创建并运行游戏
    $game = new TetrisGame();
    $game->run();
} catch (Exception $e) {
    echo "游戏运行出错: " . $e->getMessage() . "\n";
    exit(1);
}