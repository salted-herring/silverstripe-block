<?php

class DualColumnBlock extends Block {
	protected static $singular_name = 'Dual-column Block';
	protected static $db = array (
		'LeftColumn'		=>	'HTMLText',
		'RightColumn'	=>	'HTMLText'
	);
}