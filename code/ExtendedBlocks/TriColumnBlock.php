<?php

class TriColumnBlock extends Block {
	protected static $db = array (
		'LeftColumn'		=>	'HTMLText',
		'MiddleColumn'	=>	'HTMLText',
		'RightColumn'	=>	'HTMLText'
	);
}