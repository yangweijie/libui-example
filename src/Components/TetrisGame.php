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
    private ScorePanel $scorePanel;
    private ControlsPanel $controlsPanel;
    private $area;
    private $nextPieceArea;
    
    public function __construct() {
        $this->gameState = new GameState();
        $this->renderer = new GameRenderer();
        $this->nextPieceRenderer = new NextPieceRenderer();
        $this->scorePanel = new ScorePanel();
        $this->controlsPanel = new ControlsPanel();
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
        // 创建窗口 (宽度: 500, 高度: 700)
        $this->window = Window::create("Glimmer Tetris", 500, 700, 0);
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
        
        // 创建垂直布局用于信息显示
        $infoLayout = Box::newVerticalBox();
        Box::setPadded($infoLayout, true);
        Box::append($mainLayout, $infoLayout, 0);
        
        // 添加分数面板
        Box::append($infoLayout, $this->scorePanel->getControl(), false);
        
        // 创建下一个方块标签
        $nextLabel = Label::create("Next Piece:");
        Box::append($infoLayout, $nextLabel, false);
        
        // 创建下一个方块显示区域
        $this->nextPieceArea = null; // 初始化为null
        $nextPieceAreaHandler = Area::handler(
            function ($handler, $area, $params) { // 绘制回调
                if ($this->gameState->getShowPreview() && $this->gameState->getNextPiece()) {
                    $this->nextPieceRenderer->render($params, $this->gameState->getNextPiece());
                }
            }
        );
        
        // 创建下一个方块显示区域
        $this->nextPieceArea = Area::create($nextPieceAreaHandler);
        Box::append($infoLayout, $this->nextPieceArea, false);
        
        // 添加控制说明面板
        Box::append($infoLayout, $this->controlsPanel->getControl(), false);
        
        // 创建游戏区域处理程序（包含绘制和按键处理）
        $this->area = null; // 初始化为null
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
        Box::append($gameLayout, $this->area, 1);
    }
    
    /**
     * 处理按键事件
     */
    private function handleKeyPress($area, $keyEvent) {
        // 只在按键按下时处理（忽略按键释放事件）
        if ($keyEvent->Up) {
            return 1;
        }
        
        if ($this->gameState->isGameOver()) {
            // 游戏结束时按空格键重新开始
            if ($keyEvent->Key == ' ') {
                $this->gameState->initializeGame();
                if ($this->area) {
                    Area::queueRedraw($this->area);
                }
                if ($this->nextPieceArea) {
                    Area::queueRedraw($this->nextPieceArea);
                }
                $this->scorePanel->updateAll(0, 0, 1);
            }
            return 1;
        }
        
        // 如果游戏暂停，只处理暂停/恢复操作
        if ($this->gameState->isPaused()) {
            if ($keyEvent->Key == 'p' || $keyEvent->Key == 'P') {
                $this->gameState->setPaused(false);
            }
            return 1;
        }

        // 方向键控制
        switch ($keyEvent->Key) {
            case 'a':
            case 'A':
                $this->gameState->movePiece('left');
                if ($this->area) {
                    Area::queueRedraw($this->area);
                }
                break;
            case 'd':
            case 'D':
                $this->gameState->movePiece('right');
                if ($this->area) {
                    Area::queueRedraw($this->area);
                }
                break;
            case 's':
            case 'S':
                $this->gameState->movePiece('down');
                if ($this->area) {
                    Area::queueRedraw($this->area);
                }
                break;
            case 'w':
            case 'W':
                $this->gameState->rotatePiece();
                if ($this->area) {
                    Area::queueRedraw($this->area);
                }
                break;
            case ' ':
                $this->gameState->dropPiece();
                if ($this->area) {
                    Area::queueRedraw($this->area);
                }
                break;
            case 'p':
            case 'P':
                $this->gameState->setPaused(true);
                break;
            default:
                // 对于未处理的按键，返回0表示未处理
                return 0;
        }
        
        // 更新分数显示
        $this->scorePanel->updateAll(
            $this->gameState->getScore(),
            $this->gameState->getLines(),
            $this->gameState->getLevel()
        );
        
        // 重绘下一个方块区域
        if ($this->nextPieceArea) {
            Area::queueRedraw($this->nextPieceArea);
        }
        
        // 返回1表示已处理按键事件
        return 1;
    }
    
    /**
     * 启动自动下落定时器
     */
    private function startFallingTimer(): void {
        // 启动自动下落定时器
        App::timer(500, function () { // 每500毫秒下落一次
            if (!$this->gameState->isGameOver() && !$this->gameState->isPaused()) {
                $this->gameState->movePiece('down');
                if ($this->area) {
                    Area::queueRedraw($this->area);
                }
                $this->scorePanel->updateAll(
                    $this->gameState->getScore(),
                    $this->gameState->getLines(),
                    $this->gameState->getLevel()
                );
            }
            return 1; // 继续定时器
        });
    }
}