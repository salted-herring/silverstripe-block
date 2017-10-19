<?php

class DualColumnBlock extends Block {
	private static $singular_name = 'Dual-column Block';
	private static $db = array (
		'LeftColumn'		=>	'HTMLText',
		'RightColumn'	=>	'HTMLText'
	);
}