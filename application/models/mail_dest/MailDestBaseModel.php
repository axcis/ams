<?php

/**
 * MailDestBaseModel
 * @author takanori_gozu
 *
 */
class MailDestBaseModel extends MY_Model {
	
	/**
	 * メール送信グループ
	 */
	public function get_mail_group_map($no_select_show = true) {
		
		$this->set_table(MailGroupDao::TABLE_NAME, self::DB_MASTER);
		
		$this->add_select(MailGroupDao::COL_ID);
		$this->add_select_as(MailGroupDao::COL_GROUP_NAME, 'name');
		
		$list = $this->do_select();
		
		$map = array();
		
		if ($no_select_show) $map[''] = '送信グループを選択';
		$map += $this->key_value_map($list);
		
		return $map;
	}
}
?>