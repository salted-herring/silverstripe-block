<?php

class MultiClassSelector extends GridFieldAddNewMultiClass {
	private static $allowed_actions = array(
		'handleAdd'
	);

	// Should we add an empty string to the add class dropdown?
	private static $showEmptyString = true;

	private $fragment;

	private $title;

	private $classes;

	private $defaultClass;


	public function getHTMLFragments($grid) {
		$classes = $this->getClasses($grid);

		if(!count($classes)) {
			return array();
		}

		GridFieldExtensions::include_requirements();

		$field = new DropdownField(sprintf('%s[ClassName]', __CLASS__), '', $classes, $this->defaultClass);
		$field->setAttribute('id', uniqid());
		if (Config::inst()->get('GridFieldAddNewMultiClass', 'showEmptyString')) {
			$field->setEmptyString(_t('GridFieldExtensions.SELECTTYPETOCREATE', '(Select type to create)'));
		}
		$field->addExtraClass('no-change-track');

		$data = new ArrayData(array(
			'Title'      => $this->getTitle(),
			'Link'       => Controller::join_links($grid->Link(), 'add-multi-class', '{class}'),
			'ClassField' => $field
		));

		return array(
			$this->getFragment() => $data->renderWith('GridFieldAddNewMultiClass')
		);
	}
}