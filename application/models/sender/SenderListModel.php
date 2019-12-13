<?php

/**
 * SenderListModel
 * @author takanori_gozu
 *
 */
class SenderListModel extends MY_Model {
	
	/**
	 * 一覧
	 */
	public function get_list() {
		
		$this->set_table(SenderDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(SenderDao::COL_ID);
		$this->add_select(SenderDao::COL_SENDER_NAME);
		$this->add_select(SenderDao::COL_MAIL_ADDRESS);
		
		return $this->do_select();
	}
	
	/**
	 * 項目名
	 */
	public function get_list_col() {
		
		$list_cols = array();
		
		$list_cols[] = array('width' => 70, 'value' => '編集');
		$list_cols[] = array('width' => 70, 'value' => 'ID');
		$list_cols[] = array('width' => 300, 'value' => '送信者名');
		$list_cols[] = array('width' => 150, 'value' => 'メールアドレス');
		
		return $list_cols;
	}
	
	/**
	 * リンク
	 */
	public function get_link() {
		
		$link_list = array();
		
		$link_list[] = array('url' => 'sender/SenderRegist/regist_input', 'class' => 'far fa-edit', 'value' => '登録');
		
		return $link_list;
	}
}
?>