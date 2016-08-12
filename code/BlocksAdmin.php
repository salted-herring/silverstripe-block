<?php use SaltedHerring\Debugger as Debugger;
/**
 * @file BlocksAdmin.php
 *
 * Left-hand-side tab : Admin Blocks
 * */
class BlocksAdmin extends ModelAdmin {
	private static $managed_models = array('Block');
	private static $url_segment = 'blocks';
	private static $menu_title = 'Blocks';
	private static $menu_priority = 10;
	private static $menu_icon = 'silverstripe-block/images/icon-block.png';
		
	public function getEditForm($id = null, $fields = null) {
		$form = parent::getEditForm($id, $fields);
		
		$grid = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
		
		$grid->getConfig()
			->removeComponentsByType('GridFieldPaginator')
			->removeComponentsByType('GridFieldAddNewButton')
			->removeComponentsByType('GridFieldPrintButton')
			->removeComponentsByType('GridFieldExportButton')
			->removeComponentsByType('GridFieldDetailForm')
			->addComponents(
				new VersionedForm(),
				new GridFieldPaginatorWithShowAll(30),
				$multiClass = new MultiClassSelector(),
				$sortable = new GridFieldOrderableRows('SortOrder')
			);
					
		$subBlocks = self::getAvaiableTypes();
		$multiClass->setClasses($subBlocks);
		$grid->setTitle('All Blcoks');
		return $form;
	}
	
	public function getList() {
		$list = Versioned::get_by_stage('Block', 'Stage');
		
		return $list;
    }
	
	public static function getAvaiableTypes() {
		$subBlocks = ClassInfo::subclassesFor('Block');
		if (is_null($subBlocks)) {
			$subBlocks = array('Block');
		}else{
			$disabledTypes = Config::inst()->get('Block','DisabledBlocks');
			if (!empty($disabledTypes)) {
				foreach ($disabledTypes as $disabledType) {
					unset($subBlocks[$disabledType]);
				}
			}
			foreach ($subBlocks as $key => &$value) {
				$value = empty($key::$singular_name) ? ucwords(trim(strtolower(preg_replace('/_?([A-Z])/', ' $1', $value)))) : $key::$singular_name;
			}
		}
		
		return $subBlocks;
	}
	
	
}
