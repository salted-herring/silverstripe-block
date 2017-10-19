<?php

class BlockinPage extends Extension {
	private static $belongs_many_many = array (
		'Blocks'			=>	'Block'
	);
	
	public function updateCMSFields( FieldList $fields ) {
		$ancestry = ClassInfo::ancestry($this->owner->ClassName);
		if (!in_array('RedirectorPage', $ancestry) && !in_array('VirtualPage', $ancestry)) {
			$blocks = $this->owner->Blocks();
			$blocks_grid = $this->gridBuilder('Blocks', $blocks, '', true,'GridFieldConfig_RelationEditor');
			$docked_grid = $this->gridBuilder('DockedBlocks', $this->dockedBlocks(), '');
			
			$fields->addFieldToTab('Root.MyBlocks', $blocks_grid);
			$fields->addFieldToTab('Root.DockedBlocks', $docked_grid);
		}
	}
	
	private function gridBuilder($name, $source, $label = '', $canAdd = false, $gridHeaderType = 'GridFieldConfig_RecordEditor') {
		/*
		GridFieldConfig_Base
		GridFieldConfig_RecordViewer
		GridFieldConfig_RecordEditor
		GridFieldConfig_RelationEditor
		*/
		if ($label == '') { $label = $name; }
		$grid = new GridField($name, $label, $source);
		$config = $gridHeaderType::create();
		$config->removeComponentsByType('GridFieldAddNewButton');
		if ( $canAdd ) {
			$config
			->removeComponentsByType('GridFieldPaginator')
			->removeComponentsByType('GridFieldDetailForm')
			->addComponents(
				new VersionedForm(),
				new GridFieldPaginatorWithShowAll(30),
				$multiClass = new MultiClassSelector(),
				$sortable = new OrderableBlocks('SortOrder')
			);
			$subBlocks = BlocksAdmin::getAvaiableTypes();
			$multiClass->setClasses($subBlocks);
		}
		$grid->setConfig($config);
		return $grid;
	}
		
	private function dockedBlocks() {
		$blocks = Block::get();
		$IDs = array();
		$ClassName = $this->owner->ClassName;
		$Classes = $blocks->map('ID', 'shownInClass');
		foreach ($Classes as $BlockID => $Class) {
			$listedClasses = explode(',', $Class);
			if (in_array($ClassName, $listedClasses)) {
				$IDs[] = $BlockID;
			}
		}
		$blocks = Block::get()->filter('ID', $IDs)->sort('SortOrder','ASC');
		return $blocks;
	}
}