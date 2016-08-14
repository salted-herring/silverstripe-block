<?php

class OrderableBlocks extends GridFieldOrderableRows {
	protected function reorderItems($list, array $values, array $order) {
		$pool = array_values($values);
		sort($pool);

		foreach(array_values($order) as $pos => $id) {
			if($values[$id] != $pool[$pos]) {
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