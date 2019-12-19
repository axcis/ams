<?php

/**
 * TopPageModel
 * @author takanori_gozu
 *
 */
class TopPageModel extends MY_Model {
	
	/**
	 * 配信タイプ
	 * (グループ配信 or スポット配信)
	 */
	public function get_deliver_type_map($no_select_show = true) {
		
		$map = array();
		
		if ($no_select_show) $map[''] = '配信タイプを選択';
		$map['1'] = 'グループ配信';
		$map['2'] = 'スポット配信';
		
		return $map;
	}
	
	/**
	 * 配信済みメール一覧
	 */
	public function get_delivered_mail_list($search = null) {
		
		$this->set_table(SendHistoryDao::TABLE_NAME, self::DB_TRAN);
		
		$this->add_select(SendHistoryDao::COL_ID);
		$this->add_select(SendHistoryDao::COL_SEND_TYPE);
		$this->add_select(SendHistoryDao::COL_SEND_TIME);
		$this->add_select(SendHistoryDao::COL_SUBJECT);
		
		if ($search != null) {
			$this->set_search($search, SendHistoryDao::COL_SEND_TYPE, 'search_send_type');
		}
		
		//最新10件を取得する
		$this->add_order(SendHistoryDao::COL_SEND_TIME, self::ORDER_DESC);
		$this->add_limit(10);
		
		$list = $this->do_select();
		
		$deliver_type_map = $this->get_deliver_type_map(false);
		
		foreach ($list as &$row) {
			$row[SendHistoryDao::COL_SEND_TYPE] = $deliver_type_map[$row[SendHistoryDao::COL_SEND_TYPE]];
		}
		
		return $list;
	}
	
	/**
	 * 項目名
	 */
	public function get_list_col() {
		
		$list_cols = array();
		
		$list_cols[] = array('width' => 70, 'value' => 'ID');
		$list_cols[] = array('width' => 150, 'value' => '配信タイプ');
		$list_cols[] = array('width' => 150, 'value' => '配信日時');
		$list_cols[] = array('width' => 300, 'value' => '件名');
		$list_cols[] = array('width' => 120, 'value' => '詳細');
		
		return $list_cols;
	}
}
?>