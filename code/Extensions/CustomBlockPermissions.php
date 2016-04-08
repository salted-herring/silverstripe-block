<?php 

class CustomBlockPermission extends DataExtension implements PermissionProvider {
	public function canConfigPageAndType($member = false) {
		return Permission::check(get_class($this->owner) . '_config_page_and_type');
	}
	
	public function canConfigMemberVisibility($member = false) {
		return Permission::check(get_class($this->owner) . '_config_member_visibility');
	}
	
	public function providePermissions() {
		$permissions = array();
		foreach ($this->getClassList() as $class) {
			foreach (array(
				'config_page_and_type',
				'config_member_visibility'
			) as $name) {
				$permissions[$class . '_' . $name] = $class . '_' . $name;
			}
		}
		return $permissions;
	}
	
	private function getClassList() {
		$classes = array();
		foreach (ClassInfo::subclassesFor('DataObject') as $class => $file) {
			$extensions = Config::inst()->get($class, 'extensions');
			if (!is_null($extensions)) {
				if (in_array(get_class($this), $extensions)) {
					$classes[] = $class;
				}
			}
		}
		return $classes;
	}
}