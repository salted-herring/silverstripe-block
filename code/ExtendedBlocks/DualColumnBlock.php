<?php

class DualColumnBlock extends Block {
	protected static $db = array (
		'LeftColumn'		=>	'HTMLText',
		'RightColumn'	=>	'HTMLText'
	);
}