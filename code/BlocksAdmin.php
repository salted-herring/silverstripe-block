<?php
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
				->addComponents(
					new GridFieldPaginatorWithShowAll(30),
					$multiClass = new GridFieldAddNewMultiClass(),
					$sortable = new GridFieldOrderableRows('SortOrder')
				);
			
			$subBlocks = ClassInfo::subclassesFor('Block') || array();
			unset($subBlocks['Block']);
			
			$multiClass->setClasses($subBlocks);
			$grid->setTitle('All Blcoks');
			return $form;
		}
	}