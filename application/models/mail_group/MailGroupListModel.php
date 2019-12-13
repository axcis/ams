<?php

/**
 * MailGroupListModel
 * @author takanori_gozu
 *
 */
class MailGroupListModel extends MY_Model {
	
	/**
	 * 一覧
	 */
	public function get_list($search = null) {
		
		$this->set_table(MailGroupDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailGroupDao::COL_ID);
		$this->add_select(MailGroupDao::COL_GROUP_NAME);
		
		if ($search != null) {
			$this->set_search_like($search, MailGroupDao::COL_GROUP_NAME, 'search_group_name');
		}
		
		return $this->do_select();
	}
	
	/**
	 * 項目名
	 */
	public function get_list_col() {
		
		$list_cols = array();
		
		$list_cols[] = array('width' => 70, 'value' => '編集');
		$list_cols[] = array('width' => 70, 'value' => 'ID');
		$list_cols[] = array('width' => 150, 'value' => 'グループ名');
		
		return $list_cols;
	}
	
	/**
	 * リンク
	 */
	public function get_link() {
		
		$link_list = array();
		
		$link_list[] = array('url' => 'mail_group/MailGroupRegist/regist_input', 'class' => 'far fa-edit', 'value' => '登録');
		
		return $link_list;
	}
}
?>