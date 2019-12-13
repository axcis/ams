<?php

/**
 * SendHistoryListModel
 * @author takanori_gou
 *
 */
class SendHistoryListModel extends SendHistoryBaseModel {
	
	/**
	 * 一覧
	 */
	public function get_list($search = null) {
		
		$this->set_table(SendHistoryDao::TABLE_NAME, self::DB_TRAN);
		
		$this->add_select(SendHistoryDao::COL_ID);
		
	}
}
?>