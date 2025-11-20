<?php

namespace LibUI\Components;

use Kingbes\Libui\Box;
use Kingbes\Libui\Label;

class ControlsPanel {
    private $container;
    private $controlsLabel;
    
    public function __construct() {
        $this->createUI();
    }
    
    /**
     * Create the UI elements for the controls panel
     */
    private function createUI(): void {
        // Create container
        $this->container = Box::newVerticalBox();
        Box::setPadded($this->container, true);
        
        // Create controls label
        $this->controlsLabel = Label::create("Controls:\nA - Left\nD - Right\nS - Down\nW - Rotate\nSpace - Hard Drop");
        
        // Add label to container
        Box::append($this->container, $this->controlsLabel, false);
    }
    
    /**
     * Get the container control
     * @return mixed
     */
    public function getControl() {
        return $this->container;
    }
}
