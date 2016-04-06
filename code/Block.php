<?php

class Block extends DataObject {
	protected static $db = array (
		'SortOrder'			=>	'Int',
		'Title'				=>	'Varchar(64)',
		'hideTitle'			=>	'Boolean',
		'showBlockbyClass'	=>	'Boolean',
		'Description'		=>	'Varchar(128)',
		'MemberVisibility'	=>	'Varchar(255)',
		'shownInClass'		=>	'Text'
	);
	
	protected static $many_many = array (
		'Pages'				=>	'Page'
	);
		
	protected static $create_table_options = array(
		'MySQLDatabase'		=> 'ENGINE=MyISAM'
    );
	
	protected static $extensions = array (
		'StandardPermissions'
	);
	
	protected static $summary_fields = array(
		'BlockType',
		'Title', 
		'Description',
		'shownOn'
	);
	
	protected static $field_labels = array(
		'BlockType'			=>	'Block type',
		'shownOn'			=>	'is shown on'
	);
	
	public function BlockType() {
		return $this->singular_name();
	}
	
	public function shownOn() {
		if ($this->showBlockbyClass) {
			if (strlen(trim($this->shownInClass)) > 0) {
				$lists = 'Type: ' . str_replace(',','<br />Type: ', $this->shownInClass);
			}else{
				$lists = '<em>&lt;not assigned&gt;</em>';
			}
		}else{
			$lists = 'Page: ' . implode('<br />Page: ', $this->Pages()->column('Title'));
		}
		return new LiteralField('shownOn',$lists);
	}
	
	
	public function getCMSFields() {
		$fields = parent::getCMSFields();
		$fields->removeFieldFromTab('Root', 'Pages');
		$fields->removeFieldsFromTab('Root.Main', array(
			'SortOrder',
			'showBlockbyClass',
			'shownInClass',
			'MemberVisibility'
		));
		
		$memberGroups = Group::get();
		$sourcemap = $memberGroups->map('Code', 'Title');
		$source = array(
			'anonymous'		=>	'Anonymous visitors'
		);
		foreach ($sourcemap as $mapping => $key) {
			$source[$mapping] = $key;
		}
		
		$memberVisibility = new CheckboxSetField(
			$name = "MemberVisibility",
			$title = "Show block for specific groups",
			$source
		);
		
		$memberVisibility->setDescription('Show this block only for the selected group(s). If you select no groups, the block will be visible to all members.');
		
		$availabelClasses = $this->availableClasses();
		$inClass = new CheckboxSetField(
			$name = "shownInClass",
			$title = "Show block for specific content types",
			$availabelClasses
		);
		
		$filterSelector = OptionsetField::create(
			'showBlockbyClass',
			'Choose filter set',
			array(
				'0'			=>	'by page',
				'1'			=>	'by page/data type'
			)
		)->setDescription('<p><br /><strong>by page</strong>: block will be displayed in the selected page(s)<br /><strong>by page/data type</strong>: block will be displayed on the pages created with the particular page/data type. e.g. is <strong>"InternalPage"</strong> is picked, the block will be displayed, and will ONLY be displayed on all <strong>Internal Pages</strong></p>');
		
		$availablePages = Page::get()->exclude('ClassName', array(
			'ErrorPage',
			'RedirectorPage',
			'VirtualPage'
		));
		$pageSelector = new CheckboxSetField(
			$name = "Pages",
			$title = "Show on Page(s)",
			$availablePages
		);
		
		$fields->addFieldsToTab('Root.VisibilitySettings', array(
			$filterSelector,
			$pageSelector,
			$inClass,
			$memberVisibility
		));
		return $fields;
	}
	
	private function availableClasses() {
		$Classes = array_diff(
			ClassInfo::subclassesFor('Page'),
			ClassInfo::subclassesFor('RedirectorPage'),
			ClassInfo::subclassesFor('VirtualPage')
		);
		return $Classes;
	}
	
	public function forTemplate() {
		if ($this->canDisplayMemberCheck()) {
			return $this->renderWith(array($this->getClassName(), 'BaseBlock'));
		}
		
		return false;
	}
	
	private function canDisplayMemberCheck() {
		$rawVisibility = $this->MemberVisibility;
		
		if (empty($rawVisibility)) {
			return true;
		}
		
		$visibility = explode(',', $rawVisibility);
		$member = Member::currentUser();
		
		if (!$member && in_array('anonymous', $visibility)) {
			return true;
		}
		
		if ($member) {
			$memberGroups = $member->Groups()->column('Code');
			foreach ($memberGroups as $memberGroup) {
				if (in_array($memberGroup, $visibility)) {
					return true;
				}
			}
		}
		
		return false;
	}
	
	public function frontendEditable() {
		$member = Member::currentUser();		
		return $this->canEdit($member);
	}
	
	public function Type2Class() {
		return strtolower(str_replace(' ', '-', $this->singular_name()));
	}
}