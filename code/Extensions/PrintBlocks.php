<?php
class PrintBlocks extends Extension {
	
	public function getAllBlocks() {
		$blocks = Block::get();
		foreach ($blocks as $block) {
			if ($block->forTemplate() === false) {
				$blocks->exclude(array('ID' => $block->ID));
			}
		}
		return $blocks;
	}
		
	public function getMyBlocks() {
		$mySegment = ltrim($this->owner->Link(), '/' );
		$mySegment = rtrim($mySegment, '/');
		$mySegment = strlen($mySegment) == 0 || $mySegment == 'home' ? '<home>' : $mySegment;
		$blocks = Block::get()->filter(array(
			'ExcludeListed'		=>	false,
			'UrlsRegExps'		=>	$mySegment
		))->sort('SortOrder', 'ASC');
		
		return $blocks;
	}
	
	public function getGivenBlocks() {
		$blocks = $this->getAllBlocks();
		$myBlocksID = $this->getMyBlocks()->column('ID');
		
		return $blocks->exclude(array('ID' => $myBlocksID));
	}
}