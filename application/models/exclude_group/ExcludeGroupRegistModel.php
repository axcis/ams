<?php

/**
 * ExcludeGroupRegistModel
 * @author takanori_gozu
 *
 */
class ExcludeGroupRegistModel extends MY_Model {
	
	/**
	 * 詳細
	 */
	public function get_info($id) {
		
		$this->set_table(ExcludeGroupDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_where(ExcludeGroupDao::COL_ID, $id);
		
		return $this->do_select_info();
	}
	
	/**
	 * バリデーション
	 */
	public function validation($input) {
		
		$group_name = $input['group_name'];
		
		$msgs = array();
		
		//未入力チェック
		if (trim($group_name) == '') $msgs[] = $this->lang->line('err_required', array($this->lang->line('group_name')));
		
		//文字列長チェック
		if (mb_strlen(trim($group_name)) > 50) $msgs[] = $this->lang->line('err_max_length', array($this->lang->line('group_name'), 50));
		
		return $msgs;
	}
	
	/**
	 * 新規登録
	 */
	public function db_regist($input) {
		
		$this->set_table(ExcludeGroupDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_col_val(ExcludeGroupDao::COL_GROUP_NAME, $input['group_name']);
		
		$this->do_insert();
	}
	
	/**
	 * 更新
	 */
	public function db_modify($input) {
		
		$this->set_table(ExcludeGroupDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_col_val(ExcludeGroupDao::COL_GROUP_NAME, $input['group_name']);
		
		$this->add_where(ExcludeGroupDao::COL_ID, $input['id']);
		
		$this->do_update();
	}
	
	/**
	 * 削除
	 */
	public function db_delete($id) {
		
		$this->set_table(ExcludeGroupDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_where(ExcludeGroupDao::COL_ID, $id);
		
		$this->do_delete();
	}
}
?>