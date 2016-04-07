<?php

define('BLOCK_DIR',basename(dirname(__FILE__)));
LeftAndMain::require_javascript( BLOCK_DIR. '/js/silverstripe-block.script.js' );
Requirements::css(BLOCK_DIR.'/css/blocks.css');