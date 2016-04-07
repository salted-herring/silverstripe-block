<?php
class PrintBlocks extends Extension {
	
	public function getAllBlocks() {
		$blocks = new ArrayList();
		$blocks->merge($this->getMyBlocks());
		$blocks->merge($this->getDockedBlocks());
		return $blocks;
	}
		
	public function getMyBlocks() {
		return $this->owner->Blocks()->sort('SortOrder', 'ASC');
	}
	
	public function getDockedBlocks() {
		$blocks = Block::get()->filter('showBlockbyClass', true);
		$blocks_map = $blocks->map('ID', 'shownInClass');
		foreach ($blocks_map as $blockID => $Classes) {
			$Classes = explode(',', $Classes);
			if (!in_array($this->owner->ClassName, $Classes)) {
				$blocks = $blocks->exclude('ID', $blockID);
			}
		}
		
		return $blocks->sort('SortOrder', 'ASC');
	}
}