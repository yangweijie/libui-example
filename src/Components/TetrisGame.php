<?php

namespace LibUI\Components;

use Kingbes\Libui\App;
use Kingbes\Libui\Window;
use Kingbes\Libui\Control;
use Kingbes\Libui\Box;
use Kingbes\Libui\Label;
use Kingbes\Libui\Area;

class TetrisGame {
    private $window;
    private GameState $gameState;
    private GameRenderer $renderer;
    private NextPieceRenderer $nextPieceRenderer;
    private $area;
    private $nextPieceArea;
    private $scoreLabel;
    
    public function __construct() {
        $this->gameState = new GameState();
        $this->renderer = new GameRenderer();
        $this->nextPieceRenderer = new NextPieceRenderer();
    }
    
    /**
     * 运行游戏
     */
    public function run(): void {
        // 初始化libui应用
        App::init();
        
        // 设置窗口
        $this->setupWindow();
        
        // 显示窗口
        Control::show($this->window);
        
        // 启动自动下落定时器
        $this->startFallingTimer();
        
        // 主循环
        App::main();
    }
    
    /**
     * 停止游戏
     */
    public function stop(): void {
        App::quit();
    }
    
    /**
     * 设置窗口
     */
    private function setupWindow(): void {
        // 创建窗口 (宽度: 500, 高度: 650)
        $this->window = Window::create("Tetris", 500, 650, 0);
        Window::setMargined($this->window, true);
        
        // 设置窗口关闭事件
        Window::onClosing($this->window, function() {
            $this->stop();
            return 1;
        });
        
        // 创建主水平布局
        $mainLayout = Box::newHorizontalBox();
        Box::setPadded($mainLayout, true);
        Window::setChild($this->window, $mainLayout);
        
        // 创建垂直布局用于游戏区域
        $gameLayout = Box::newVerticalBox();
        Box::setPadded($gameLayout, true);
        Box::append($mainLayout, $gameLayout, 1);
        
        // 创建标签显示"Game"
        $gameLabel = Label::create("Game");
        Box::append($gameLayout, $gameLabel, 0);
        
        // 创建游戏区域处理程序（包含绘制和按键处理）
        $areaHandler = Area::handler(
            function ($handler, $area, $params) { // 绘制回调
                $this->renderer->render($params, $this->gameState);
            },
            function ($handler, $area, $keyEvent) { // 按键回调
                return $this->handleKeyPress($area, $keyEvent);
            }
        );
        
        // 创建游戏区域
        $this->area = Area::create($areaHandler);
        Box::append($gameLayout, $this->area, 0);
        
        // 创建垂直布局用于信息显示
        $infoLayout = Box::newVerticalBox();
        Box::setPadded($infoLayout, true);
        Box::append($mainLayout, $infoLayout, 0);
        
        // 创建分数标签
        $this->scoreLabel = Label::create("Score: 0");
        Box::append($infoLayout, $this->scoreLabel, 0);
        
        // 创建下一个方块标签
        $nextLabel = Label::create("Next Piece:");
        Box::append($infoLayout, $nextLabel, 0);
        
        // 创建下一个方块显示区域处理程序
        $nextPieceAreaHandler = Area::handler(
            function ($handler, $area, $params) { // 绘制回调
                $this->nextPieceRenderer->render($params, $this->gameState->getNextPiece());
            }
        );
        
        // 创建下一个方块显示区域
        $this->nextPieceArea = Area::create($nextPieceAreaHandler);
        Box::append($infoLayout, $this->nextPieceArea, 0);
        
        // 创建控制说明标签
        $controlsLabel = Label::create("Controls:\nA - Move Left\nD - Move Right\nS - Move Down\nW - Rotate\nSpace - Drop/Restart");
        Box::append($infoLayout, $controlsLabel, 0);
    }
    
    /**
     * 处理按键事件
     */
    private function handleKeyPress($area, $keyEvent) {
        if ($this->gameState->isGameOver()) {
            // 游戏结束时按空格键重新开始
            if ($keyEvent->Key == ' ') {
                $this->gameState->initializeGame();
                Area::queueRedraw($this->area);
                Area::queueRedraw($this->nextPieceArea);
                if ($this->scoreLabel) {
                    Label::setText($this->scoreLabel, "Score: " . $this->gameState->getScore());
                }
            }
            return 1;
        }

        // 方向键控制
        switch ($keyEvent->Key) {
            case 'a':
            case 'A':
                $this->gameState->movePiece('left');
                Area::queueRedraw($this->area);
                break;
            case 'd':
            case 'D':
                $this->gameState->movePiece('right');
                Area::queueRedraw($this->area);
                break;
            case 's':
            case 'S':
                $this->gameState->movePiece('down');
                Area::queueRedraw($this->area);
                break;
            case 'w':
            case 'W':
                $this->gameState->rotatePiece();
                Area::queueRedraw($this->area);
                break;
            case ' ':
                $this->gameState->dropPiece();
                Area::queueRedraw($this->area);
                if ($this->scoreLabel) {
                    Label::setText($this->scoreLabel, "Score: " . $this->gameState->getScore());
                }
                break;
            default:
                // 对于未处理的按键，返回0表示未处理
                return 0;
        }
        
        // 更新分数显示
        if ($this->scoreLabel) {
            Label::setText($this->scoreLabel, "Score: " . $this->gameState->getScore());
        }
        
        // 重绘下一个方块区域
        Area::queueRedraw($this->nextPieceArea);
        
        // 返回1表示已处理按键事件
        return 1;
    }
    
    /**
     * 启动自动下落定时器
     */
    private function startFallingTimer(): void {
        // 启动自动下落定时器
        App::timer(500, function () { // 每500毫秒下落一次
            if (!$this->gameState->isGameOver()) {
                $this->gameState->movePiece('down');
                Area::queueRedraw($this->area);
                if ($this->scoreLabel) {
                    Label::setText($this->scoreLabel, "Score: " . $this->gameState->getScore());
                }
            }
            return 1; // 继续定时器
        });
    }
}