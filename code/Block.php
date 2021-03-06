<?php
class Block extends DataObject {
	private static $db = array (
		'SortOrder'			=>	'Int',
		'Title'				=>	'Varchar(64)',
		'TitleWrapper'		=>	'Enum("h2,h3,h4,h5,h6")',
		'hideTitle'			=>	'Boolean',
		'showBlockbyClass'	=>	'Boolean',
		'Description'		=>	'Varchar(128)',
		'MemberVisibility'	=>	'Varchar(255)',
		'shownInClass'		=>	'Text',
		'addMarginTop'		=>	'Boolean',
		'addMarginBottom'	=>	'Boolean',
		'addPaddingTop'		=>	'Boolean',
		'addPaddingBottom'	=>	'Boolean'
	);

	private static $many_many = array (
		'Pages'				=>	'Page'
	);

	private static $default_sort = array(
		'SortOrder'			=>	'ASC',
		'ID'					=>	'DESC'
	);

	private static $create_table_options = array(
		'MySQLDatabase'		=> 'ENGINE=MyISAM'
    );

	private static $extensions = array (
		'StandardPermissions'
	);

	private static $summary_fields = array(
		'BlockType',
		'Title',
		'Description',
		'shownOn',
		'VisibleTo',
		'Published'
	);

	private static $field_labels = array(
		'BlockType'			=>	'Block type',
		'shownOn'			=>	'is shown on',
		'VisibleTo'			=>	'Visible to'
	);

	public function VisibleTo() {
		if (strlen(trim($this->MemberVisibility)) > 0) {
			$lists = 'Group: ' . str_replace(',','<br />Group: ', $this->MemberVisibility);
		}else{
			$lists = '<em>&lt;All&gt;</em>';
		}

		return new LiteralField('VisibleTo',$lists);
	}

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
			if ($this->Pages()->count() > 0) {
				$lists = 'Page: ' . implode('<br />Page: ', $this->Pages()->column('Title'));
			}else{
				$lists = '<em>&lt;not assigned&gt;</em>';
			}
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

		$fields->addFieldToTab('Root.Main', LiteralField::create('Status', 'Published: ' . $this->Published()), 'Title');

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
			$availablePages->map('ID','Title')
		);


		if ($this->canConfigPageAndType(Member::currentUser())) {
			$fields->addFieldsToTab('Root.VisibilitySettings', array(
				$filterSelector,
				$pageSelector,
				$inClass
			));
		}

		if ($this->canConfigMemberVisibility(Member::currentUser())) {
			$fields->addFieldToTab('Root.VisibilitySettings', $memberVisibility);
		}

		if (!$fields->fieldByName('Options')) {
			$fields->insertBefore($right = RightSidebar::create('Options'), 'Root');
	    }

	    $fields->addFieldsToTab('Options', array(
			CheckboxField::create('addMarginTop', 'add "margin-top" class to block wrapper'),
			CheckboxField::create('addMarginBottom', 'add "margin-bottom" class to block wrapper'),
            		CheckboxField::create('addPaddingTop', 'add "padding-top" class to block wrapper'),
			CheckboxField::create('addPaddingBottom', 'add "padding-bottom" class to block wrapper')
	    ));

		return $fields;
	}

	public function doPublish() {
		$this->writeToStage('Live');
	}

	public function onBeforeWrite() {
		parent::onBeforeWrite();
		if (empty($this->byPass)) {
			$this->readmode = Versioned::get_reading_mode();
			Versioned::set_reading_mode('Stage.Stage');
		}
	}

	public function onAfterWrite() {
		parent::onAfterWrite();
		if (isset($this->readmode)) {
			Versioned::set_reading_mode('Stage.' . $this->readmode);
		}

		/*if ($this->isPublished()) {
			$live = Versioned::get_by_stage('Block', 'Live')->byID($this->ID);
			$stage = Versioned::get_by_stage('Block', 'Stage')->byID($this->ID);
			if ($live->SortOrder != $stage->SortOrder) {

				$this->byPass = true;
				$this->doPublish();
			}
		}*/
	}

	public function availableClasses() {
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

	public function canDisplayMemberCheck() {
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
		return $this->canEdit($member) && Config::inst()->get('Block', 'FrontendEditable');
	}

	public function Type2Class() {
		return strtolower(str_replace(' ', '-', $this->singular_name()));
	}

	/*public function doPublish() {
		$this->writeToStage('Live');
	}*/

	public function Published() {
		return $this->isPublished() ? 'Yes' : 'No';
	}

	public function isPublished() {
		if (!empty(Versioned::get_by_stage('Block', 'Live')->byID($this->ID))) {
			return true;
		}

		return false;
	}

}
