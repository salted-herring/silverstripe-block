<?php

define('BLOCK_DIR',basename(dirname(__FILE__)));
Requirements::block('rightsidebar/js/cms.js');
LeftAndMain::require_javascript( BLOCK_DIR. '/js/silverstripe-block.script.js' );
Requirements::css(BLOCK_DIR.'/css/blocks.css');