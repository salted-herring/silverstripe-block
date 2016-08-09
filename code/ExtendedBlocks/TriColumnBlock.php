<?php

class TriColumnBlock extends Block {
	protected static $singular_name = 'Triple-column Block';
	protected static $db = array (
		'LeftColumn'		=>	'HTMLText',
		'MiddleColumn'	=>	'HTMLText',
		'RightColumn'	=>	'HTMLText'
	);
}