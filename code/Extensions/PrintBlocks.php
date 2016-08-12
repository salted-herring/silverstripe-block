<?php
class PrintBlocks extends Extension {
	
	public function getAllBlocks() {
		$blocks = new ArrayList();
		$blocks->merge($this->getMyBlocks());
		$blocks->merge($this->getDockedBlocks());
		return $blocks;
	}
		
	public function getMyBlocks() {
		$blocks = $this->owner->Blocks()->sort(array('SortOrder' => 'ASC', 'ID' => 'DESC'));
		$published_blocks = new ArrayList();
		foreach ($blocks as $block) {
			if ($block->isPublished()) {
				$published_blocks->push($block);
			}
		}
		
		return $published_blocks;
	}
	
	public function getDockedBlocks() {
		$blocks = Block::get()->filter(array('showBlockbyClass' => true));
		$blocks_map = $blocks->map('ID', 'shownInClass');
		foreach ($blocks_map as $blockID => $Classes) {
			$Classes = explode(',', $Classes);
			if (!in_array($this->owner->ClassName, $Classes)) {
				$blocks = $blocks->exclude('ID', $blockID);
			}
		}
		$published_blocks = new ArrayList();
		foreach ($blocks as $block) {
			if ($block->isPublished()) {
				$published_blocks->push($block);
			}
		}
		
		return $published_blocks->sort('SortOrder', 'ASC');
	}
}