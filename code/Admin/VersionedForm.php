<?php

use SaltedHerring\Debugger as Debugger;

class VersionedForm extends GridFieldDetailForm {
	
}

class VersionedForm_ItemRequest extends GridFieldDetailForm_ItemRequest {
	private static $allowed_actions = array(
		'edit',
		'view',
		'ItemEditForm',
		'doPublish',
		'doUnpublish'
	);
		
	public function ItemEditForm() {
		$form = parent::ItemEditForm();
		if ($form instanceof Form) {
			$actions = $form->Actions();
			$record = $this->record;
			$actions->insertBefore('action_doDelete', $btnPublish = FormAction::create('doPublish','Save &amp; Publish'));
			$btnPublish->addExtraClass('ss-ui-action-constructive');
			if (!empty($record->ID)) {
				if ($record->isPublished()) {
					$actions->removeByName('action_doDelete');
					$actions->push(FormAction::create('doUnpublish', 'Unpublish')->addExtraClass('ss-ui-action-destructive'));
				}
			}
			
			$form->setActions($actions);
		}
		return $form;
	}
	
	public function doUnpublish($data, $form) {
		$this->record->deleteFromStage('Live');
		$controller = Controller::curr();
		$form->sessionMessage('Block unpublished', 'good', false);
		return $this->edit($controller->getRequest());
	}
	
	public function doPublish($data, $form) {
		$form->saveInto($this->record);
		$this->record->write();
		$this->record->byPass = true;
		$this->record->doPublish();
		$form->sessionMessage('Block published', 'good', false);
		$controller = Controller::curr();
		return $this->edit($controller->getRequest());
	}
}