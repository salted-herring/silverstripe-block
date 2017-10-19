<?php

class TriColumnBlock extends Block {
	private static $singular_name = 'Triple-column Block';
	private static $db = array (
		'LeftColumn'		=>	'HTMLText',
		'MiddleColumn'	=>	'HTMLText',
		'RightColumn'	=>	'HTMLText'
	);
}