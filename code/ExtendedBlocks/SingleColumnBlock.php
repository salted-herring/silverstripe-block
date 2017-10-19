<?php

class SingleColumnBlock extends Block {
	private static $singular_name = 'Single-column Block';
	private static $db = array (
		'Content'	=>	'HTMLText'
	);
}