<?php

class OrderableBlocks extends GridFieldOrderableRows {
	protected function reorderItems($list, array $values, array $order) {
		// Get a list of sort values that can be used.
		$pool = array_values($values);
		sort($pool);

		// Loop through each item, and update the sort values which do not
		// match to order the objects.
		foreach(array_values($order) as $pos => $id) {
			if($values[$id] != $pool[$pos]) {
				//SaltedHerring\Debugger::inspect($q);
				DB::query(sprintf(
					'UPDATE "%s" SET "%s" = %d WHERE %s',
					$this->getSortTable($list),
					$this->getSortField(),
					$pool[$pos],
					$this->getSortTableClauseForIds($list, $id)
				));
				DB::query(sprintf(
					'UPDATE "%s_Live" SET "%s" = %d WHERE %s',
					$this->getSortTable($list),
					$this->getSortField(),
					$pool[$pos],
					$this->getSortTableClauseForIds($list, $id)
				));
			}
		}

		$this->extend('onAfterReorderItems', $list);
	}
}