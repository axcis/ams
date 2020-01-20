<?php

/**
 * MailDestBaseModel
 * @author takanori_gozu
 *
 */
class MailDestBaseModel extends MY_Model {
	
	/**
	 * 除外グループ
	 */
	public function get_exclude_group_map($no_select_show = true) {
		
		$this->set_table(ExcludeGroupDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(ExcludeGroupDao::COL_ID);
		$this->add_select_as(ExcludeGroupDao::COL_GROUP_NAME, 'name');
		
		$list = $this->do_select();
		
		$map = array();
		
		if ($no_select_show) $map[''] = '除外グループを選択';
		$map[0] = '除外なし';
		$map += $this->key_value_map($list);
		
		return $map;
	}
}
?>