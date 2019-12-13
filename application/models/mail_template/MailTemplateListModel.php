<?php

/**
 * MailTemplateListModel
 * @author takanori_gozu
 *
 */
class MailTemplateListModel extends MY_Model {
	
	/**
	 * 一覧
	 */
	public function get_list($search = null) {
		
		$this->set_table(MailTemplateDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailTemplateDao::COL_ID);
		$this->add_select(MailTemplateDao::COL_TEMPLATE_NAME);
		$this->add_select(MailTemplateDao::COL_SUBJECT);
		
		if ($search != null) {
			$this->set_search_like($search, MailTemplateDao::COL_TEMPLATE_NAME, 'search_template_name');
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
		$list_cols[] = array('width' => 150, 'value' => 'テンプレート名');
		$list_cols[] = array('width' => 150, 'value' => '件名');
		$list_cols[] = array('width' => 120, 'value' => '詳細');
		
		return $list_cols;
	}
	
	/**
	 * リンク
	 */
	public function get_link() {
		
		$link_list = array();
		
		$link_list[] = array('url' => 'mail_template/MailTemplateRegist/regist_input', 'class' => 'far fa-edit', 'value' => '登録');
		
		return $link_list;
	}
	
	/**
	 * 詳細
	 */
	public function get_info($id) {
		
		$this->set_table(MailTemplateDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_where(MailTemplateDao::COL_ID, $id);
		
		return $this->do_select_info();
	}
}
?>