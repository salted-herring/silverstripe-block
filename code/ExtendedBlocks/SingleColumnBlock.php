<?php

class SingleColumnBlock extends Block {
	protected static $singular_name = 'Single-column Block';
	protected static $db = array (
		'Content'	=>	'HTMLText'
	);
}