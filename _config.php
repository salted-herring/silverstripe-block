<?php
define('BLOCK_DIR', basename(dirname(__FILE__)));
LeftAndMain::require_javascript(BLOCK_DIR. '/js/silverstripe-block.script.js');
$url        =   ltrim($_REQUEST['url'], '/');
$segments   =   explode('/', $url);
if (count($segments) > 0) {
    if ($segments[0] == 'admin') {
        Requirements::css(BLOCK_DIR.'/css/blocks.css');
    }
}
