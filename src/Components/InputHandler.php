<?php

namespace LibUI\Components;

class InputHandler {
    private GameState $gameState;
    private $area;
    
    public function __construct(GameState $gameState, $area) {
        $this->gameState = $gameState;
        $this->area = $area;
    }
    
    /**
     * 处理按键事件
     * @param string $key 按键字符
     * @return bool
     */
    public function handleKeyPress(string $key): bool {
        // 如果游戏结束，只处理重启按键
        if ($this->gameState->isGameOver()) {
            $this->processGameRestart($key);
            return true;
        }
        
        // 处理移动按键
        $this->processMovement($key);
        
        // 处理旋转按键
        $this->processRotation($key);
        
        // 处理立即下落按键
        $this->processDrop($key);
        
        return true;
    }
    
    /**
     * 处理移动操作
     * @param string $key
     */
    private function processMovement(string $key): void {
        switch ($key) {
            case 'A':
            case 'a':
                $this->gameState->movePiece('left');
                break;
            case 'D':
            case 'd':
                $this->gameState->movePiece('right');
                break;
            case 'S':
            case 's':
                $this->gameState->movePiece('down');
                break;
        }
    }
    
    /**
     * 处理旋转操作
     * @param string $key
     */
    private function processRotation(string $key): void {
        if ($key === 'W' || $key === 'w') {
            $this->gameState->rotatePiece();
        }
    }
    
    /**
     * 处理立即下落操作
     * @param string $key
     */
    private function processDrop(string $key): void {
        if ($key === ' ') { // 空格键立即下落
            $this->gameState->dropPiece();
        }
    }
    
    /**
     * 处理游戏重启
     * @param string $key
     */
    private function processGameRestart(string $key): void {
        if ($key === ' ') { // 空格键重启游戏
            $this->gameState->initializeGame();
        }
    }
}